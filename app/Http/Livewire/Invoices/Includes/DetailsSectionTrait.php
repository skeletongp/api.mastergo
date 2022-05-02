<?php

namespace App\Http\Livewire\Invoices\Includes;

trait DetailsSectionTrait
{
    public $producto;
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
            $this->updatedUnitId();
        } else {
            $this->reset('form', 'product', 'cant', 'product_code', 'price', 'discount', 'total');
        }
    }
    public function addItems()
    {
        // $this->removeIfExists();
        $this->form['id'] = count($this->details);
        $this->form['cant'] = $this->cant;
        $this->form['price'] = $this->price;
        $this->form['subtotal'] =  $this->cant * $this->price;
        $this->form['discount_rate'] =  ($this->discount / 100);
        $this->form['discount'] = ($this->form['subtotal'] * ($this->discount / 100));
        $this->form['totalTax'] = $this->totalTax;
        $this->form['total'] = ($this->form['subtotal'] - $this->form['discount']) + $this->totalTax;
        $this->form['utility'] = ($this->form['cant'] * $this->form['price']) - ($this->form['cant'] * $this->form['cost']);
        $this->form['user_id'] = auth()->user()->id;
        $this->form['store_id'] = auth()->user()->store->id;
        $this->form['place_id'] = auth()->user()->place->id;
        $this->form['product_name'] = $this->product['name'];
        $this->form['product_code'] = $this->product['code'];
        $this->form['taxes'] = $this->product['taxes'];

        $this->validate();
        array_push($this->details, $this->form);
        $this->emit('addDetailToJS', $this->details);
        $this->reset('form', 'product', 'cant', 'product_code', 'price', 'discount', 'total');
    }
    public function removeItem($id)
    {
        unset($this->details[$id]);
    }
    public function updatedUnitId()
    {
        $unit = auth()->user()->place->units()->wherePivot('id', $this->unit_id)->first();
        if ($unit) {
            if ($this->cant >= $unit->pivot->min) {
                $this->price = formatNumber($unit->pivot->price_mayor);

                $this->form['price_type'] = 'mayor';
            } else {
                $this->price = formatNumber($unit->pivot->price_menor);
                $this->form['price_type'] = 'detalle';
            }
            $this->form['cost'] = $unit->pivot->cost;
            $this->form['unit_name'] = $unit->name;
            $discount = 0;
            if ($this->discount) {
                $discount = $this->discount;
            }
            $sub = str_replace(',', '', formatNumber(($this->cant * $this->price) * (1 - ($discount / 100))));
            if ($this->product) {
                $this->totalTax = str_replace(',', '', formatNumber(($sub * $this->producto->taxes->sum('rate'))));
            }
            $this->total = str_replace(',', '', formatNumber($sub + $this->totalTax));
        }
    }
}
