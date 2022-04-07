<?php

namespace App\Http\Livewire\Recursos;

use Livewire\Component;

class CreateRecurso extends Component
{
    public $form, $units;
    public function render()
    {
        $this->units=auth()->user()->store->units->pluck('name','id');
        return view('livewire.recursos.create-recurso');
    }

    protected $rules = [
        'form.name' => 'required|string|min:7|max:50',
        'form.description' => 'required|string',
        'form.cost' => 'required|numeric|min:1',
        'form.cant' => 'required|numeric|min:1',
        'form.unit_id' => 'required|numeric|exists:units,id',
        'form.place_id' => 'required|numeric|exists:places,id',
    ];

    public function createRecurso()
    {
        $this->form['place_id']=auth()->user()->place->id;
        $this->validate();
        $store=auth()->user()->store;
        $store->recursos()->create($this->form);
        $this->reset('form');
        $this->emit('showAlert','Recurso registrado exitosamente','success');
        $this->emit('refreshLivewireDatatable');

    }
}
