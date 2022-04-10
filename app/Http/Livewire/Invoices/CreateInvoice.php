<?php

namespace App\Http\Livewire\Invoices;

use App\Models\Unit;
use Livewire\Component;

class CreateInvoice extends Component
{

    public $form, $maxCant=0;
    public $details=[];

    public $products, $producto;

    public Unit $unit;

    protected $listeners=['selProducto'];

    protected $rules=[
        'form.cant'=>'numeric|min:0.01|required',
        'form.price'=>'numeric|min:0.01|required',
        'form.cost'=>'numeric|min:0.01|required',
    ];

    public function mount()
    {
        $this->products=auth()->user()->store->products->load('units');
    }

    public function render()
    {
        return view('livewire.invoices.create-invoice');
    }

    public function addItems()
    {
        $this->validate();
        array_push($this->details,
        [
            'cant'=>$this->form['cant'],
            'price'=>$this->form['price'],
            'cost'=>$this->form['cost'],
            'utility'=>($this->form['cant']*$this->form['price'])-($this->form['cant']*$this->form['cost']),
            'total'=>($this->form['cant']*$this->form['price']),
            'product_id'=>$this->form['product_id'],
            'unit_id'=>$this->form['unit_id'],
            'user_id'=>auth()->user()->id,
            'store_id'=>auth()->user()->store->id,
            'place_id'=>auth()->user()->place->id,
            'product_name'=>$this->producto->name,
            'unit_name'=>$this->unit->name,
        ]);
        $this->reset('form');
    }
    public function selProducto($id)
    {
       $this->producto=$this->products->where('id', $id)->first();
       $this->form['unit_id']=$this->producto->units()->first()->id;
       $this->selUnit();
       $this->render();
    }
    public function selUnit()
    {
        $this->unit=$this->producto->units()->where('unit_id', $this->form['unit_id'])->first();
        $this->maxCant=$this->unit->pivot->stock;
        $this->form['price']=$this->unit->pivot->price;
        $this->form['cost']=$this->unit->pivot->cost;
    }
}
