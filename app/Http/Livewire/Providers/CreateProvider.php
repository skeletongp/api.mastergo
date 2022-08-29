<?php

namespace App\Http\Livewire\Providers;

use Livewire\Component;

class CreateProvider extends Component
{
    public $form, $provDocType;
    protected $querystring = ['provDocType'];
    protected $rules = [
        'form.name' => 'required|string|max:100',
        'form.email' => 'required|email|max:100|unique:providers,email',
        'form.address' => 'required|string|max:150',
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
    public function loadFromRNC(){
        $url='contribuyentes/'.$this->form['rnc'];
        $prov=getApi($url);
        if (array_key_exists('model', $prov)) {
            $this->form['name']=$prov['model']['name'];
           
        }
    }
}
