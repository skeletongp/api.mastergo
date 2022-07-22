<?php

namespace App\Http\Livewire\Clients;

use App\Models\Client;
use Mediconesystems\LivewireDatatables\Action;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ClientInvoice extends LivewireDatatable
{
    public $headTitle = "Historial de compras";
    public $client;
    public function builder()
    {
        $client=$this->client;
        $invoices = $client->invoices()->where('status','!=','waiting')->orderBy('created_at', 'desc')->with('payment', 'client', 'seller', 'contable', 'payments');
        return $invoices;
    }
   
    public function columns()
    {
        $invoices = $this->builder()->get()->toArray();
        return [
            Column::checkbox(),
            Column::name('id')->callback(['id'], function ($id) use ($invoices) {
                $result = arrayFind($invoices, 'id', $id);
                if ($result['rest'] > 0) {
                    return "  <a href=" . route('invoices.show', [$id, 'includeName' => 'showpayments', 'includeTitle' => 'Pagos']) .
                        "><span class='fas w-8 text-center fa-dollar-sign'></span> </a>";
                } else {
                    return "  <a href=" . route('invoices.show', $id) . "><span class='fas w-8 text-center fa-eye'></span> </a>";
                }
            })->label(''),
            DateColumn::name('created_at')->label('Hora')->format('h:i A'),
            Column::name('condition')->label('Condición')->filterable(['De Contado','1 A 15 Días', '16 A 30 Días']),
            Column::callback(['uid', 'id'], function ($total, $id) use ($invoices) {
                $result = arrayFind($invoices, 'id', $id);
                return '$' . formatNumber($result['payment']['total']);
            })->label("Monto"),
            Column::name('client.id')->callback(['id', 'client_id'], function ($id, $client_id) use ($invoices) {
                $result = arrayFind($invoices, 'id', $id);
                return '$' . formatNumber(array_sum(array_column($result['payments'], 'payed')));
            })->label('Pagado'),
            Column::name('rest')->callback(['rest'], function ($rest) {
                return '$' . formatNumber($rest);
            })->label('Resta')->searchable(),
        ];
    }

    public function buildActions()
    {
        return [

            Action::value('edit')->label('Cobrar facturas')->callback(function ($mode, $items) {
              return redirect()->route('clients.paymany', compact('items'));
            }),

        ];
    }
}