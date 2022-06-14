<?php

namespace App\Http\Livewire\Providers;

use App\Models\Provider;
use Livewire\Component;

class EditProvider extends Component
{

    public $provider, $provDocType;

    protected $rules = [
        'provider'=>'required',
        'provider.name' => 'required|string|max:50',
        'provider.lastname' => 'required|string|max:75',
        'provider.email' => 'required|email|max:100|unique:clients,email',
        'provider.address' => 'required|string|max:100',
        'provider.limit' => 'required|numeric|min:0',
        'provider.phone' => 'required|string|max:25',
        'provider.rnc' => 'required|string|max:25',
        'provDocType' => 'required',
    ];

    public function mount($provider)
    {
        
        
        $this->provider=$provider;
    }
    public function render()
    {
        return view('livewire.providers.edit-provider');
    }
    public function updateProvider()
    {
        $this->validate();
        $provider=Provider::find($this->provider['id']);
        $provider->update($this->provider);
        $this->emit('showAlert','Datos actualizados correctamente','success');
        $this->emit('refreshLivewireDatatable');
    }
}
