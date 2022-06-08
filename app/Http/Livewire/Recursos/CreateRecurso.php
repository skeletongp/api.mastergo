<?php

namespace App\Http\Livewire\Recursos;

use App\Http\Livewire\Recursos\Includes\BrandTrait;
use Livewire\Component;

class CreateRecurso extends Component
{
    use BrandTrait;
    public $form, $units, $providers;
    public function render()
    {
        $this->units = auth()->user()->store->units->pluck('name', 'id');
        $this->providers = auth()->user()->store->providers->pluck('fullname', 'id');
        return view('livewire.recursos.create-recurso');
    }

    protected $rules = [
        'form.name' => 'required|string|max:50',
        'form.unit_id' => 'required|numeric|exists:units,id',
        'form.place_id' => 'required|numeric|exists:places,id',
        'form.provider_id' => 'required|numeric|exists:places,id',
        'brands'=>'required'
    ];

    public function createRecurso()
    {
        $this->form['place_id'] = auth()->user()->place->id;
        $this->validate();
        $store = auth()->user()->store;
        $recurso = $store->recursos()->create($this->form);
        $this->createBrands($recurso);
        $this->reset('form', 'brands');
        $this->emit('showAlert', 'Recurso registrado exitosamente', 'success');
        $this->emit('refreshLivewireDatatable');
    }
}
