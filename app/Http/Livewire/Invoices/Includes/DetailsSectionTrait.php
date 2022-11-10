<?php

namespace App\Http\Livewire\Invoices\Includes;

use function PHPUnit\Framework\never;
trait DetailsSectionTrait
{
    public $oldPrice;
    public $producto;
    public $product, $product_code, $product_name, $products=[], $stock, $unit, $open = false;


    function rules()
    {
        return invoiceCreateRules();
    }

    public function setProduct($product_code)
    {
        $code = str_pad($product_code, 3, '0', STR_PAD_LEFT);
        //get track of function that is calling this function
     
        $product = auth()->user()->place->products()->where('code', $code)->first();
        $this->producto = $product;
        if ($product) {
            $productLoad = [
                'name' => $product->name,
                'code' => $product->code,
                'units' => $product->units,
                'type' => $product->type,
                'taxes' => $product->taxes()->pluck('taxes.id')
            ];
            $this->form['product_id'] = $product->id;
            $this->unit_id = $product->units->first()->pivot->id;
            $this->product = collect($productLoad);
            $this->product_code = $code;
            $this->product_name = $product->name;
            $this->freshUnitId();
        } else {
            $this->reset('form', 'product', 'cant', 'product_code', 'price', 'discount', 'total', 'product_name', 'taxTotal');
        }
    }
    public function tryAddItems()
    {
        $this->setManualDiscount($this->oldPrice, $this->price);
        $this->validate(['product' => 'required']);
        $place=getPlace();
        $this->number =getPlace()->id . '-' . str_pad( getNumberFromInvoice() + 1, 7, '0', STR_PAD_LEFT);
        /* if ($this->cant > $this->stock && !auth()->user()->hasPermissionTo('Autorizar') && $this->product['type'] != 'Servicio') {
            $this->authorize('Vender producto fuera de Stock', 'validateAuthorization','confirmedAddItems','data=null','Autorizar');
        } else { */
            $this->confirmedAddItems();
            $this->number = $place->id . '-' . str_pad(getNumberFromInvoice() + 1, 7, '0', STR_PAD_LEFT);
       /*  } */
        $this->emit('focusCode');
    }
   
    public function updatedCant()
    {
        if ($this->producto) {
            $unt = $this->producto->units()->where('units.id', $this->unit->id)->first()->pivot;
            $min = $unt->min;
            if ($this->client && $this->client['special']) {
                $this->price = $unt->price_special;
            } elseif ($this->cant >= $min) {
                $this->price = $unt->price_mayor;
            }
            $pr = removeComma($this->price);
            $sub = removeComma(formatNumber((floatVal($this->cant)  * $pr) * (1 - ($this->discount / 100))));
            if ($this->product) {
                $this->taxTotal = $sub * $this->producto->taxes->sum('rate');
                $this->checkStock();
            }
            $this->total =removeComma(formatNumber($sub + $this->taxTotal)  );
        }
    }
    public function confirmedAddItems()
    {
        $this->price = str_replace(',', '', $this->price);
        $this->form['id'] = count($this->details);
        $this->form['cant'] = $this->cant;
        $this->form['price'] = str_replace(',', '', $this->price);
        $this->validate();
        $this->form['subtotal'] =  operate($this->cant, '*', $this->price);
        $this->form['discount_rate'] =  ($this->discount / 100);
        $this->form['discount'] = (operate($this->form['subtotal'], '*', ($this->discount / 100)));
        $this->form['taxTotal'] = $this->taxTotal;
        $this->form['total'] = ($this->form['subtotal'] - $this->form['discount']) + $this->taxTotal;
        $this->form['utility'] = ($this->form['cant'] * $this->form['price']) - ($this->form['cant'] * $this->form['cost']);
        $this->form['unit_id'] = $this->unit->id;
        $this->form['unit_pivot_id'] = $this->pivot_id;
        $this->form['user_id'] = auth()->user()->id;
        $this->form['store_id'] = auth()->user()->store->id;
        $this->form['place_id'] = auth()->user()->place->id;
        $this->form['product_name'] = $this->product['name'];
        $this->form['product_code'] = $this->product['code'];
        $this->form['taxes'] = $this->product['taxes'];

        array_push($this->details, $this->form);
        $this->emit('focusCode');

        $this->reset('form', 'product', 'cant', 'product_code', 'price', 'discount', 'total', 'product_name', 'taxTotal');
    }
    public function removeItem($id)
    {
        //dd($id, $this->details);
        $this->form['product_id'] = $this->details[$id]['product_id'];
        unset($this->details[$id]);
        $this->details = array_values($this->details);
        if (count($this->details)) {
            foreach ($this->details as $ind => $det) {
                $this->details[$ind]['id'] = $ind;
            }
        }
        $this->checkStock();
    }
    public function editItem($id)
    {
        $this->form = $this->details[$id];
        $this->product_code = $this->form['product_code'];
        $this->setProduct($this->product_code);
        $this->product_name = $this->form['product_name'];
        $this->cant = $this->form['cant'];
        $this->price = $this->form['price'];
        $this->discount = ($this->form['discount'] / ($this->form['cant'] * $this->form['price'])) * 100;
        $this->total = $this->form['total'];
        $this->taxTotal = $this->form['taxTotal'];
        $this->unit_id = $this->form['unit_pivot_id'];
        $this->pivot_id = $this->form['unit_pivot_id'];
        $this->removeItem($id);
        $this->emit('focusCode');
    }

    public function checkStock()
    {
        $exist = array_keys(array_column($this->details, 'product_id'), $this->form['product_id']);
        if ($exist) {
            foreach ($exist as $key) {
                if ($this->details[$key]['unit_id'] == $this->unit->id) {
                    $this->stock = $this->stock - $this->details[$key]['cant'];
                }
                $this->details = array_values($this->details);
            }
        }
    }
    public function freshUnitId()
    {
        $unit = auth()->user()->place->units()->wherePivot('id', $this->unit_id)->first();
        if ($unit) {
            $this->unit = $unit;
            $this->price = $unit->pivot->price_menor;
            $this->stock = $unit->pivot->stock;
            if ($this->client && $this->client['special']) {
                $this->price = $unit->pivot->price_special;
                $this->form['price_type'] = 'detalle';
            } else if ($this->cant >= $unit->pivot->min) {
                $this->price = $unit->pivot->price_mayor;
                $this->form['price_type'] = 'mayor';
            } else {
                $this->price = $unit->pivot->price_menor;
                if($this->discount<1){
                    $this->discount = $unit->pivot->discount;
                }
                $this->form['price_type'] = 'detalle';
            }


            $this->form['unit_name'] = $unit->symbol;
            
            $pr = removeComma($this->price);
            $sub = removeComma(formatNumber((floatVal($this->cant)  * $pr) * (1 - ($this->discount / 100))));
            if ($this->product) {
                $this->form['cost'] = $unit->pivot->cost;
                $this->taxTotal =(floatVal($this->cant)  * $pr) * $this->producto->taxes->sum('rate');
                $this->checkStock();
            }
            $this->total =removeComma(formatNumber($sub + $this->taxTotal)  );
            $this->pivot_id = $unit->pivot->id;

        }
    }
    public function isScan($code)
    {
        if(substr($code,0,4)=='scan'){
            $codeArray=explode('|',$code);
            $this->setProduct($codeArray[1]);
            $this->cant=$codeArray[2]; 
            $this->tryAddItems();

            return true;
        }
        return false;
    }
   
    public function updatedProductCode()
    {
        $code = substr($this->product_code, 0, 3);
        if(!$this->isScan($this->product_code)){
            $this->setProduct($code);
        }
        $this->invoice=null;
    }
    public function updatedProductName()
    {
        $code = substr($this->product_name, 0, 3);
        $this->setProduct($code);
    }
    public function updatedUnitId()
    {
        $this->freshUnitId();
    }
    public function updatingPrice($newPrice)
    {
        $this->oldPrice = $this->price;
        $oldPrice = floatVal($this->price) ?: 0.0001;
        
        
        $this->freshUnitId();

        $pr = removeComma($newPrice);
        $sub = removeComma(formatNumber((floatVal($this->cant)  * $pr) * (1 - ($this->discount / 100))));
        if ($this->product) {

            $this->taxTotal = $sub * $this->producto->taxes->sum('rate');
            $this->checkStock();
        }
        $this->total = str_replace(',', '', formatNumber($sub + $this->taxTotal));
        $this->setManualDiscount($oldPrice, $newPrice);
        
    }
    public function updatedPrice($oldPrice){
    }

    public function setManualDiscount($oldPrice, $newPrice){
        if($oldPrice>$newPrice){
            $this->discount = ($oldPrice - $newPrice) / $oldPrice * 100;
           $this->price=$oldPrice;
            
        }
    }

    public function updatingDiscount($desc)
    {
        if ($desc && !is_nan($desc)) {
            $this->discount = $desc * 100;
        }
    }
}
