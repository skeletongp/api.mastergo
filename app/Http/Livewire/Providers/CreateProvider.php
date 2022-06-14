<?php

namespace App\Http\Livewire\Providers;

use Livewire\Component;

class CreateProvider extends Component
{
    public $form, $provDocType;

    protected $rules = [
        'form.name' => 'required|string|max:50',
        'form.lastname' => 'required|string|max:75',
        'form.email' => 'required|email|max:100|unique:clients,email',
        'form.address' => 'required|string|max:100',
        'form.limit' => 'required|numeric|min:0',
        'form.phone' => 'required|string|max:25',
        'form.rnc' => 'required|string|max:25',
        'provDocType' => 'required',
    ];
    public function render()
    {
        
        return view('livewire.providers.create-provider');
    }
    public function createProvider()
    {
       $this->validate();
       $store = auth()->user()->store;
       $provider = $store->providers()->create($this->form);
       setContable($provider, '201', 'credit');
       $this->reset();
       $this->render();
       $this->emit('showAlert', 'Proveedor registrado exitosamente', 'success');
       $this->emit('refreshLivewireDatatable');
    }
}
