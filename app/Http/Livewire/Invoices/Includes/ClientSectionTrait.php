<?php

namespace App\Http\Livewire\Invoices\Includes;

use App\Models\Client;
use Illuminate\Support\Facades\DB;

trait ClientSectionTrait
{

    public $client, $client_code, $clients, $name, $rnc;

    public function changeClient()
    {
       
        $code = str_pad($this->client_code, 4, '0', STR_PAD_LEFT);
        $client = Client::where('code', $code)->first();
        if ($client) {
            $this->client = [
                'fullname' => $client->fullname,
                'address' => $client->address,
                'phone' => $client->phone,
                'email' => $client->email,
                'rnc' => $client->rnc,
                'id' => $client->id,
                'balance' => '$' . formatNumber($client->limit),
                'limit' => $client->limit,
            ];
            $this->emit('focusCode');
            $this->client_code = $code;
        }
    }
    public function realoadClients()
    {
        $this->clients = auth()->user()->store->clients()->orderBy('name')->pluck('name', 'code');
    }

    public function updatedClientCode()
    {
        $this->clients = auth()->user()->store->clients()->orderBy('name')->pluck('name', 'code');
        $this->changeClient();
    }
    public function rncEnter()
    {
        $url='contribuyentes/'.$this->name;
        $client=Client::whereRaw("REPLACE(rnc,'-','')=?", [$this->name])
        ->orWhere('name',$this->name)->first();
        if ($client) {
            $this->client_code=$client->code;
            $this->changeClient();
            $this->name=null;
            return;
        }
        $client=getApi($url);
        if (array_key_exists('model', $client)) {
            $this->loadFromRNC($client['model']);
        }
    }
    public function loadFromRNC($client)
    {
       $this->rnc=$client['id'];
       $this->name=$client['name'];
       $this->render();
    }
}
