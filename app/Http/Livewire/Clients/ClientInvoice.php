<?php

namespace App\Http\Livewire\Clients;

use App\Http\Classes\NumberColumn;
use App\Models\Client;
use App\Models\Invoice;
use Mediconesystems\LivewireDatatables\Action;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ClientInvoice extends LivewireDatatable
{
    public $headTitle = "Historial de compras";
    public $client_id;
    public $padding = "px-2";
    public $total=0;
    public $hideable='select';
    public $hideResults = true;

    public function builder()
    {
       if($this->total>0){
        $this->headTitle = "Pendiente de pago $".formatNumber($this->total);
       } else{
        $this->headTitle = "Historial de compras";
       }
        $invoices = 
        Invoice::where('invoices.client_id',$this->client_id)
        ->where('invoices.status','!=','waiting')
        ->leftJoin('payments', 'payments.payable_id', '=', 'invoices.id')
        ->where('payments.payable_type', '=', 'App\Models\Invoice')
        ->leftJoin('moso_master.users as seller', 'seller.id', '=', 'invoices.seller_id')
        ->leftJoin('moso_master.users as contable', 'contable.id', '=', 'invoices.contable_id')
        ->groupBy('invoices.id')
      ;
        return $invoices;
    }
   
    public function columns()
    {
        return [
            Column::checkbox(),
            Column::callback('invoices.number', function($number){
                return "<a class='hover:underline hover:text-blue-500' href=".route('invoices.show',$number).">".ltrim(substr($number, strpos($number, '-') + 1), '0')."</a>";
            })->label('Nro.'),
            Column::callback(['invoices.id','invoices.rest'], function ($id,$rest)  {
                if ($rest > 0) {
                    return "  <a href=" . route('invoices.show', [$id, 'includeName' => 'showpayments', 'includeTitle' => 'Pagos']) .
                        "><span class='fas w-8 text-center fa-dollar-sign'></span> </a>";
                } else {
                    return "  <a href=" . route('invoices.show', $id) . "><span class='fas w-8 text-center fa-eye'></span> </a>";
                }
            })->label(''),
            DateColumn::name('invoices.created_at')->label('Fecha')->format('d/m/Y h:i A')->hide(),
            Column::name('invoices.condition')->label('Condición')->filterable(['De Contado','Contra Entrega','1 A 15 Días', '16 A 30 Días']),
            NumberColumn::raw('SUM(payments.payed-payments.cambio)+invoices.rest AS monto')->label('Monto')->formatear('money'),
            NumberColumn::raw('SUM(payments.payed-payments.cambio) AS pago')->label('Pagado')->formatear('money'),
            NumberColumn::raw('invoices.rest AS resta')->label('Resta')->formatear('money'),
          
        ];
    }

    public function buildActions()
    {
        return [

            Action::value('edit')->label('Cobrar facturas')->callback(function ($mode, $items) {
              return redirect()->route('clients.paymany', ['invoices'=>implode(',',$items)]);
            }),
            Action::value('print')->label('Imprimir facturas')->callback(function ($mode, $items) {
                return redirect()->route('clients.printmany', ['invoices'=>implode(',',$items)]);
              }),

        ];
    }
    public function updatedSelected(){
        $rest=Invoice::whereIn('id', $this->selected)->sum('rest');
        $this->total=$rest;
        $this->builder();
    }
}