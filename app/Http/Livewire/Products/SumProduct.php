<?php

namespace App\Http\Livewire\Products;

use App\Models\Product;
use Livewire\Component;

class SumProduct extends Component
{
    public $products, $units, $form, $productAdded = [];

    protected $listeners=['getUnits'];

    public function mount()
    {
        $this->products = auth()->user()->place->products->pluck('name', 'id');
        $this->units = auth()->user()->place->units->pluck('name', 'id');
    }

    protected $rules1 = [
        'form.product_id' => 'required|exists:products,id',
        'form.cant' => 'required|numeric|min:1',
        'form.unit' => 'required|numeric|exists:units,id',
    ];
    protected $rules = [
        'productAdded' => 'required|min:1'
    ];

    public function render()
    {
        return view('livewire.products.sum-product');
    }
    public function addProduct()
    {
        $this->validate($this->rules1);
        $this->form['product_name'] = $this->products[$this->form['product_id']];
        $exist = in_array([$this->form['product_id'], $this->form['unit']], [
            array_column($this->productAdded, 'product_id'),
            array_column($this->productAdded, 'unit')
        ]);
        if (!$exist) {
            $this->form['id'] = count($this->productAdded);
            array_push($this->productAdded, $this->form);
        } else {
            $this->emit('showAlert', 'El producto ya ha sido añadido', 'warning');
        }
        $this->reset('form');
    }
    public function remove($id)
    {
        unset($this->productAdded[$id]);
        $this->render();
    }

    public function sumCant()
    {
        $this->validate();
        foreach ($this->productAdded as $added) {
            $unit = auth()->user()->place->units()
                ->where('product_id', $added['product_id'])
                ->where('unit_id', $added['unit'])->first();
           
            $unit->pivot->stock = $unit->stock + $added['cant'];
            $unit->pivot->save();
        }
        $this->reset('form','productAdded');
        $this->emit('showAlert','Productos añadidos al stock','success');
    }
    public function getUnits()
    {
        $prod=Product::find($this->form['product_id']);
        $this->units=$prod->units()->distinct('name')->pluck('name','units.id');
    }
}
