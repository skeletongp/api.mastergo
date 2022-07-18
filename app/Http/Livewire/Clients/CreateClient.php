<?php

namespace App\Http\Livewire\Clients;

use App\Models\Client;
use App\Models\CountMain;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateClient extends Component
{
    public $form, $avatar, $photo_path, $store_id, $role, $cltDocType, $name, $lastname, $cellphone, $cedula;
    use WithFileUploads;

    public function render()
    {

        $store = auth()->user()->store;
        $num = $store->clients()->count() + 1;
        $code = str_pad($num, 3, '0', STR_PAD_LEFT);
        $this->form['code'] = $code;
        return view('livewire.clients.create-client');
    }
    protected $rules = [
        'form.name' => 'max:50',
        'form.email' => 'required|email|max:100|unique:clients,email',
        'form.address' => 'required|string|max:100',
        'form.limit' => 'required|numeric|min:0',
        'form.phone' => 'required|string|max:25',
        'form.rnc' => 'required|string|max:25',
        'name' => 'required|string|max:50',
        'lastname' => 'required|string|max:75',
        'cellphone' => 'required|string|max:25',
        'cltDocType' => 'required',
    ];
    public function createClient()
    {
        if (empty($this->form['limit'])) {
            $this->form['limit'] = 0.00;
        }
        $store = auth()->user()->store;
        $this->validate();
        $client = $store->clients()->create($this->form);
            $client->contact()->create([
                'name' => $this->name,
                'lastname' => $this->lastname,
                'cellphone' => $this->cellphone,
                'cedula' => $this->cedula,
                'phone'=>$this->form['phone'],
            ]);
        if ($this->photo_path) {
            $client->image()->create([
                'path' => $this->photo_path
            ]);
        }
        setContable($client, '101', 'debit', $client->contact->fullname.'-'.$client->name, null, true);
        $this->emit('realoadClients');

        $this->reset();
        $this->render();
        $this->emit('showAlert', 'Cliente registrado exitosamente', 'success');
        $this->emit('refreshLivewireDatatable');
    }
    public function updatedAvatar()
    {
        $ext = pathinfo($this->avatar->getFileName(), PATHINFO_EXTENSION);
        $photo = $this->avatar->storeAs('clients', date('Y_m_d_H_i_s') . '.' . $ext);
        $this->photo_path = asset("storage/{$photo}");
    }
    function loadFromRNC()
    {
        if (array_key_exists('rnc', $this->form)) {
            $rnc=str_replace('-', '', $this->form['rnc']);
            $client = getApi('contribuyentes/' . $rnc);
            if ($client && array_key_exists('model', $client)) {
                $client = $client['model'];
                if (strlen($rnc) == 9){
                    $this->form['name'] = $client['name'];
                } else {
                    $this->name = strtok($client['name'], ' ');
                    $this->lastname=substr($client['name'],strlen($this->name));
                }
                
            }
        }
    }
}
