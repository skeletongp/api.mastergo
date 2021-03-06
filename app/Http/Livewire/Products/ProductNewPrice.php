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
       $units=auth()->user()->store->units()->pluck('name','units.id');
       $hasUnit=$this->product->units()->pluck('name','units.id');
       $this->units=array_diff($units->toArray(), $hasUnit->toArray());
       
    }
    protected $rules=[
        'form'=>'required',
        'form.*.price_menor'=>'required|min:1',
        'form.*.price_mayor'=>'required|min:1',
        'form.*.min'=>'required|min:1',
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
        $cost=$data['cost']?:0.0001;
        $data['margin'] = $data['price_menor']/$cost-1;
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
