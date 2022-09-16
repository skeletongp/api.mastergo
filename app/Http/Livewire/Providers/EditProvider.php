<?php

namespace App\Http\Livewire\Providers;

use App\Models\Provider;
use Livewire\Component;

class EditProvider extends Component
{

    public $provider=[], $provDocType, $provider_id;
    protected $listeners = ['modalOpened' => 'modalOpened'];
    protected $rules = [
        'provider' => 'required',
        'provider.name' => 'required|string|max:50',
        'provider.email' => 'required|email|max:100|unique:clients,email',
        'provider.address' => 'required|string|max:100',
        'provider.limit' => 'required|numeric|min:0',
        'provider.phone' => 'required|string|max:25',
        'provider.rnc' => 'required|string|max:25',
        'provDocType' => 'required',
    ];

    public function mount($provider_id)
    {

        $this->provider_id = $provider_id;
    }
    public function render()
    {
        return view('livewire.providers.edit-provider');
    }
    public function modalOpened()
    {
        $this->provider = Provider::find($this->provider_id)->toArray();
    }
    public function updateProvider()
    {
        $this->validate();
        $provider = Provider::find($this->provider['id']);
        $provider->update($this->provider);
        $this->emit('showAlert', 'Datos actualizados correctamente', 'success');
        $this->emit('refreshLivewireDatatable');
    }
}
