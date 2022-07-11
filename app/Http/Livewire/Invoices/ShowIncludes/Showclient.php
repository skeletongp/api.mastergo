<?php

namespace App\Http\Livewire\Invoices\ShowIncludes;

use App\Models\Client;

trait Showclient
{
    public $client, $clients, $client_code;

    public function changeClient()
    {
        $code = str_pad($this->client_code, 4, '0', STR_PAD_LEFT);
        $client = Client::where('code', $code)->first();
        if ($client) {
            $this->client = [
                'name' => $client->name,
                'address' => $client->address,
                'phone' => $client->phone,
                'email' => $client->email,
                'rnc' => $client->rnc ?: 'N/D',
                'id' => $client->id,
                'balance' => '$' . formatNumber($client->limit),
                'gasto' => '$' . formatNumber($client->payments()->sum('payed')),
                'limit' => $client->limit,
                'contact'=>$client->contact,
            ];
        }
        
    }
    public function updatedClientCode()
    {
        $this->changeClient();
    }

    public function tryUpdateInvoiceClient()
    {
        if (!auth()->user()->hasPermissionTo('Editar Facturas')) {
            $this->action='updateInvoiceClient';
            $this->emit('openAuthorize', 'Para cambiar cliente de factura');
        } else {
            $this->updateInvoiceClient();
        }
    }
    public function updateInvoiceClient()
    {
        $invoice = $this->invoice;
        $client = Client::whereId($this->client['id'])->first();

        if ($client->id !== $invoice->client_id) {
            $invoice->client->limit = $invoice->client->limit + $this->invoice->payment->rest;
            $invoice->client->save();
            setTransaction('Cambio de cliente Fact. No. ' . $invoice->number, $invoice->payment->ncf ?: $invoice->number, $invoice->payment->rest, $client->contable()->first(), $invoice->client->contable()->first());
            $client->limit = $client->limit - $invoice->payment->rest;
            $client->save();
            $invoice->update(['client_id' => $client->id]);
            $invoice->payment->update(['payer_id' => $client->id]);
        }
        $this->emit('showAlert', 'Cliente Actualizado', 'success');
        $this->render();
    }
}
