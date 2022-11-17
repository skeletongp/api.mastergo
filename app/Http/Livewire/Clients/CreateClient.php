<?php

namespace App\Http\Livewire\Clients;

use App\Models\Client;
use App\Models\CountMain;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateClient extends Component
{
    public $cliente=[], $avatar, $photo_path, $store_id, $role, $cltDocType, $name, $lastname, $cellphone, $cedula;
    use WithFileUploads;
    protected $listeners=['modalOpened'];
    public function mount(){
        $this->cliente['code']=0;
    }

    public function render()
    {
       
        return view('livewire.clients.create-client');
    }
    public function modalOpened(){
        $this->cliente['special']=0;
        $store = auth()->user()->store;
        if(!Cache::get('clientCount'.env('STORE_ID'))){
            Cache::put('clientCount'.env('STORE_ID'),$store->clients()->withTrashed()->count());
        }
        $num = Cache::get('clientCount'.env('STORE_ID')) + 1;
        $code = str_pad($num, 3, '0', STR_PAD_LEFT);
        $this->cliente['code'] = $code;
    }
    protected $rules = [
        'cliente.name' => 'max:50',
        'cliente.email' => 'required|email|max:100|unique:clients,email',
        'cliente.address' => 'required|string|max:100',
        'cliente.limit' => 'required|numeric|min:0',
        'cliente.phone' => 'required|string|max:25',
        'cliente.rnc' => 'required|string|max:25',
        'cliente.special' => 'required',
        'name' => 'required|string|max:50',
        'lastname' => 'required|string|max:75',
        'cellphone' => 'required|string|max:25',
        'cltDocType' => 'required',
    ];
    public function createClient()
    {
        if (empty($this->cliente['limit'])) {
            $this->cliente['limit'] = 0.00;
        }
        if (!array_key_exists('name', $this->cliente) || empty($this->cliente['name'])) {
            $this->cliente['name'] = strtoupper(strtok($this->name, ' ').' '.strtok($this->lastname, ' '));
        }
        $store = auth()->user()->store;
        $this->validate();
        $client = $store->clients()->create($this->cliente);
            $client->contact()->create([
                'name' => $this->name,
                'lastname' => $this->lastname,
                'cellphone' => $this->cellphone,
                'cedula' => $this->cedula,
                'phone'=>$this->cliente['phone'],
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
        Cache::forget('clientCount'.env('STORE_ID'));
        Cache::forget('clientsWithCode_'.env('STORE_ID'));
        $this->emit('showAlert', 'Cliente registrado exitosamente', 'success');
        $this->emit('refreshLivewireDatatable');
        $this->modalOpened();
    }
    
    public function updatedAvatar()
    {
        $ext = pathinfo($this->avatar->getFileName(), PATHINFO_EXTENSION);
        $photo = $this->avatar->storeAs('clients', date('Y_m_d_H_i_s') . '.' . $ext);
        $this->photo_path = asset("storage/{$photo}");
    }
    function loadFromRNC()
    {
        if (array_key_exists('rnc', $this->cliente)) {
            $rnc=str_replace('-', '', $this->cliente['rnc']);
            $client = getApi('contribuyentes/' . $rnc);
            if ($client && array_key_exists('model', $client)) {
                $client = $client['model'];
                if (strlen($rnc) == 9){
                    $this->cliente['name'] = $client['name'];
                } else {
                    $this->name = strtok($client['name'], ' ');
                    $this->lastname=substr($client['name'],strlen($this->name));
                }
                
            }
        }
    }
}
