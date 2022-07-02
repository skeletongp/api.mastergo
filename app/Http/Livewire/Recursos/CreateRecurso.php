<?php

namespace App\Http\Livewire\Recursos;

use App\Http\Livewire\Recursos\Includes\BrandTrait;
use Livewire\Component;

class CreateRecurso extends Component
{
    use BrandTrait;
    public $form = [], $units, $providers, $type = 'Recurso';
    public function render()
    {
        $store = auth()->user()->store;
        $this->units = $store->units()->pluck('name', 'id');
        $this->providers = $store->providers()->pluck('fullname', 'id');
        return view('livewire.recursos.create-recurso');
    }

    protected $rules = [
        'form.name' => 'required|string|max:50',
        'form.unit_id' => 'required|numeric|exists:units,id',
        'form.place_id' => 'required|numeric|exists:places,id',
        'form.provider_id' => 'required|numeric|exists:providers,id',

    ];

    public function createRecurso()
    {
        $this->form['place_id'] = auth()->user()->place->id;
        if ($this->type == 'Recurso') {
            $this->rules = array_merge($this->rules, ['brands' => 'required|min:1']);
        } else {
            $this->rules = array_merge($this->rules, ['cost' => 'required']);
        }
        $this->validate($this->rules);
        $store = auth()->user()->store;
        if ($this->type == 'Recurso') {
            $recurso = $store->recursos()->create($this->form);
            $this->createBrands($recurso);
        } else {
            $this->form['cost'] = $this->cost;
            $recurso = $store->condiments()->create($this->form);
        }
        $this->emit('clearSelect');
        $this->reset("form",'marca', 'brands', 'cost');
        $this->emit('showAlert', 'Recurso registrado exitosamente', 'success');
        $this->emit('refreshLivewireDatatable');
    }
    public function updatedType()
    {
        $this->emit('clearSelect');
    }
    
}
