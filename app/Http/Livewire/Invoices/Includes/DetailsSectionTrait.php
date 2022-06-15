<?php

namespace App\Http\Livewire\Invoices\Includes;

trait DetailsSectionTrait
{
    public $producto;
    public $product, $product_code, $product_name, $products, $stock, $unit, $open=false;


    function rules()
    {
        return invoiceCreateRules();
    }

    public function setProduct($product_code)
    {
        $code = str_pad($product_code, 3, '0', STR_PAD_LEFT);
        $product = auth()->user()->place->products()->where('code', $code)->first();
        $this->producto = $product;
        if ($product) {
            $productLoad = [
                'name' => $product->name,
                'code' => $product->code,
                'units' => $product->units,
                'taxes' => $product->taxes->pluck('id')
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
        
        if ($this->cant > $this->stock && !auth()->user()->hasPermissionTo('Autorizar')) {
            $this->action='confirmedAddItems';
           $this->emit('openAuthorize');
        } else{
            $this->confirmedAddItems();
        }
       
    }
    public function confirmedAddItems()
    {
        $this->price=str_replace(',','',$this->price);
        $this->form['id'] = count($this->details);
        $this->form['cant'] = $this->cant;
        $this->form['price'] = str_replace(',','',$this->price);
        $this->validate();
        $this->form['subtotal'] =  $this->cant * $this->price;
        $this->form['discount_rate'] =  ($this->discount / 100);
        $this->form['discount'] = ($this->form['subtotal'] * ($this->discount / 100));
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
        $this->form['product_id']=$this->details[$id]['product_id'];
        unset($this->details[$id]);
        $this->details = array_values($this->details);
        foreach ($this->details as $ind => $det) {
            $this->details[$ind]['id'] = $ind;
        }
        $this->checkStock();
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
            $this->stock = $unit->pivot->stock;
            if ($this->cant >= $unit->pivot->min) {
                $this->price = formatNumber($unit->pivot->price_mayor);

                $this->form['price_type'] = 'mayor';
            } else {
                $this->price = formatNumber($unit->pivot->price_menor);
                $this->form['price_type'] = 'detalle';
            }
            $this->form['cost'] = $unit->pivot->cost;
            $this->form['unit_name'] = $unit->symbol;
            $discount = 0;
            if ($this->discount) {
                $discount = $this->discount;
            }
           $pr=str_replace(',','',$this->price);
            $sub = str_replace(',', '', formatNumber(($this->cant  * $pr) * (1 - ($discount / 100))));
            if ($this->product) {

                $this->taxTotal = str_replace(',', '', formatNumber(($sub * $this->producto->taxes->sum('rate'))));
                $this->checkStock();
            }
            $this->total = str_replace(',', '', formatNumber($sub + $this->taxTotal));
            $this->pivot_id = $unit->pivot->id;

        }
    }
    public function updatedProductCode()
    {
        $this->setProduct($this->product_code);
    }
    public function updatingPrice($newPrice)
    {
        $oldPrice=$this->price;
        if ($oldPrice>$newPrice) {
            $discount=formatNumber(1-($newPrice/$oldPrice));
           $this->discount=$discount*100;
        }
        $this->price=$newPrice;
    }
    public function updatingDiscount($desc)
    {
      $this->discount=$desc*100;
    }
}