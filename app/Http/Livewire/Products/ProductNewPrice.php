<?php

namespace App\Http\Livewire\Products;

use App\Models\Product;
use Livewire\Component;

class ProductNewPrice extends Component
{
    public Product $product;
    public $units, $form;
    
    public function mount()
    {
       $units=auth()->user()->store->units->pluck('name','id');
       $hasUnit=$this->product->units->pluck('name','id');
       $this->units=array_diff($units->toArray(), $hasUnit->toArray());
       
    }
    protected $rules=[
        'form'=>'required',
        'form.*.price'=>'required|min:1',
        'form.*.cost'=>'required|min:1',
    ];
    public function render()
    {
        return view('livewire.products.product-new-price');
    }

    public function addPrice()
    {
        $this->validate($this->rules);
       foreach ($this->form as $key => $data) {
        $data['place_id'] = auth()->user()->place->id;
        $data['margin'] = $data['price']/$data['cost']-1;
        $data['stock']=0;
        $this->product->units()->attach(
            $key,
            $data
        );
       }
       $this->emit('showAlert', 'Precio añadido exitosamente', 'success');
       $this->emitUp('reloadEditProduct');
    }
}
