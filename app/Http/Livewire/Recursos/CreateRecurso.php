<?php

namespace App\Http\Livewire\Recursos;

use Livewire\Component;

class CreateRecurso extends Component
{
    public $form;
    public function render()
    {
        return view('livewire.recursos.create-recurso');
    }

    protected $rules = [
        'form.name' => 'required|string|min:7|max:50',
        'form.description' => 'required|string',
        'form.cost' => 'required|numeric|min:1',
        'form.cant' => 'required|numeric|min:1',
        'form.unit_id' => 'required|numeric|exists:units,id',
        'form.store_id' => 'required|numeric|exists:stores,id',
        'form.place_id' => 'required|numeric|exists:place,id',
    ];

    public function createRecurso()
    {
        $this->form['store_id']=auth()->user()->store->id;
        $this->form['place_id']=auth()->user()->place->id;
        dd($this->form);
        $this->validate();

    }
}
