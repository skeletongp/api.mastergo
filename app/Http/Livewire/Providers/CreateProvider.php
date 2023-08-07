<?php

namespace App\Http\Livewire\Providers;

use App\Models\Provider;
use Livewire\Component;

class CreateProvider extends Component
{
    public $form, $provDocType ;
    protected $querystring = ['provDocType'];

    public function rules()
    {
        return [
            'form.name' => 'required|string|max:100',
            'form.email' => 'required|email|max:100|unique:providers,email,' . $this->form['rnc'] . ',rnc',
            'form.address' => 'required|string|max:150',
            'form.limit' => 'required|numeric|min:0',
            'form.phone' => 'required|string|max:25',
        ];
    }
    public function mount(){
        $this->form['limit'] = 0;
        $this->form['rnc'] = null;
    }

    public function render()
    {
        return view('livewire.providers.create-provider');
    }
    public function createProvider()
    {
        if (!array_key_exists('email', $this->form) || $this->form['email'] == "") {
            $this->form['email'] = uniqid()."@email.com";
        }


        $this->validate();
        $store = auth()->user()->store;
        if ($this->form['rnc'] == '000-00000-0') {
            $this->form['rnc'] = '000-' . str_pad(Provider::withTrashed()->count(), 5, '0', STR_PAD_LEFT) . '-0';
        }
        $provider = $store->providers()->updateOrCreate(['rnc' => $this->form['rnc']], $this->form);
        if (!$provider->contable) {
            setContable($provider, '200', 'credit');
        }
        $this->reset();
        $this->render();
        $this->emit('showAlert', 'Proveedor registrado exitosamente', 'success');
        $this->emit('refreshLivewireDatatable');
    }
    public function loadFromRNC()
    {
        $this->validateOnly('form.rnc');
        $provider = Provider::whereRnc($this->form['rnc'])->first();
        if ($provider) {
            $this->form = $provider->toArray();
            $this->form['name'] = $provider->fullname;
            return;
        }
        $url = 'contribuyentes/';
        $rnc= str_replace('-', '', $this->form['rnc']);
        $prov = getApi($url, $rnc);
        if (count($prov)>0) {
            $prov=$prov[0];
            $this->form['name'] = $prov['model']['name'];
        }
    }
}
