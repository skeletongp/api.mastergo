<?php

namespace App\Http\Livewire\Reports;

use App\Http\Livewire\UniqueDateTrait;
use App\Http\Classes\Column;
use App\Http\Classes\NumberColumn;
use App\Models\Invoice;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class InvoicePorCobrar extends LivewireDatatable
{
    public $headTitle = "Facturas pendientes por cobrar";
    public $padding = "px-2";

    public function builder()
    {
        $place = getPlace();
        $invoices = 
        Invoice::where('invoices.place_id', $place->id)
        ->where('status', '=', 'cerrada')
        ->where('invoices.rest','>',0)
        ->leftJoin('clients','invoices.client_id','=','clients.id')
        ->leftJoin('payments','invoices.id','=','payments.payable_id')
        ->where('payments.payable_type','=',Invoice::class)
        ->groupBy('invoices.id')
       ;
        return $invoices;
    }

    public function columns()
    {
       
        return [
            Column::callback(['id','rest'], function($id, $rest){
                    return "  <a href=".route('invoices.show', [$id,'includeName'=>'showpayments','includeTitle'=>'Pagos']).
                    "><span class='fas w-8 text-center fa-hand-holding-usd'></span> </a>";
            })->label(''),
            Column::callback(['number'], function ($number) {
                return $number;
            })->label('Nro.')->searchable(),
            DateColumn::name('invoices.created_at')->label('Fecha')->format('d/m/Y h:i A')->filterable(),  
            Column::callback(['invoices.name','clients.name'], function ($name, $client)  {
                return ellipsis($name ?:$client, 20);
            })->label('Cliente')->searchable(),
            Column::name('condition')->label('Condición')->filterable([
                'De Contado', 'Contra Entrega', '1 A 15 Días', '16 A 30 Días', '31 A 45 Dïas'
            ]),
            NumberColumn::raw('payments.amount')->label('Total')->formatear('money')->filterable(),
            NumberColumn::raw('SUM(payments.efectivo-payments.cambio) AS efectivo')->label('Efectivo')->formatear('money'),
            NumberColumn::raw('SUM(payments.transferencia) AS transferencia')->label('Transf.')->formatear('money'),
            NumberColumn::raw('SUM(payments.tarjeta) AS tarjeta')->label('Otros')->formatear('money'),
            NumberColumn::raw('SUM(payments.payed-payments.cambio) AS payed')->label('Pagado')->formatear('money',' font-bold'),
            NumberColumn::raw('invoices.rest AS rest')->label('Resta')->formatear('money', 'text-red-500')->filterable(),
           
        ];
    }
}