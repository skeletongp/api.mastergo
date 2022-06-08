<?php

namespace App\Http\Livewire\Invoices\Includes;

use App\Models\Client;

trait ClientSectionTrait
{

    public $client, $client_code, $clients, $name;

    public function changeClient()
    {
        $this->clients = auth()->user()->store->clients()->orderBy('lastname')->pluck('fullname', 'code');
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
        $this->clients = auth()->user()->store->clients()->orderBy('lastname')->pluck('fullname', 'code');
    }

    public function updatedClientCode()
    {
        $this->changeClient();
    }
}
