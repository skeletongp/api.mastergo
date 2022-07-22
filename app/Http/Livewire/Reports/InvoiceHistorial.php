<?php

namespace App\Http\Livewire\Reports;

use App\Models\Payment;
use Carbon\Carbon;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\ColumnSet;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class InvoiceHistorial extends LivewireDatatable
{

    public $headTitle = "Historial de facturas";
    public $padding = "px-2";
    public $uniqueDate = true;

    public function builder()
    {
        $place = auth()->user()->place;
        $invoices = $place->invoices()->orderBy('invoices.created_at', 'desc')
            ->where('status', '=', 'cerrada')
            ->with('client', 'payments','payment');
        return $invoices;
    }

    public function columns()
    {
        $invoices = $this->builder()->get()->toArray();
        return [
            Column::name('id')->callback(['id'], function($id) use ($invoices){
                $result = arrayFind($invoices, 'id', $id);
                if ($result['rest']>0) {
                    return "  <a href=".route('invoices.show', [$id,'includeName'=>'showpayments','includeTitle'=>'Pagos']).
                    "><span class='fas w-8 text-center fa-hand-holding-usd'></span> </a>";
                } else {
                    return "  <a href=".route('invoices.show', $id)."><span class='fas w-8 text-center fa-eye'></span> </a>";
                }
            })->label(''),
            Column::callback(['number'], function ($number) {
                $number = ltrim(substr($number, strpos($number, '-') + 1), '0');
                return $number;
            })->label('Nro.')->searchable(),
            DateColumn::callback('created_at', function($date){
                return Carbon::parse($date)->format('m-d H:i');
            })->label('Fecha')->searchable(),  
            Column::callback(['name','id'], function ($client, $id) use ($invoices) {
                $result = arrayFind($invoices, 'id', $id);
                return ellipsis($result['name'] ?: ($result['client']['name']?:$result['client']['contact']['fullname']), 20);
            })->label('Cliente')->searchable(),
            Column::name('condition')->label('Condición')->filterable([
                'De Contado', 'Contra Entrega', '1 A 15 Días', '16 A 30 Días', '31 A 45 Dïas'
            ]),
            Column::callback(['store_id','id'], function ($client, $id) use ($invoices) {
                $result = arrayFind($invoices, 'id', $id);
                return '$'.formatNumber($result['payment']['total'], );
            })->label('Monto'),

            Column::callback(['place_id','id'], function ($client, $id) use ($invoices) {
                $result = arrayFind($invoices, 'id', $id);
                return '$'.formatNumber(array_sum(array_column($result['payments'], 'efectivo'))
            -array_sum(array_column($result['payments'], 'cambio')));
            })->label('Efectivo'),

            Column::callback(['client_id','id'], function ($client, $id) use ($invoices) {
                $result = arrayFind($invoices, 'id', $id);
                return '$'.formatNumber(array_sum(array_column($result['payments'], 'transferencia')));
            })->label('Transf.'),
            Column::callback(['updated_at','id'], function ($client, $id) use ($invoices) {
                $result = arrayFind($invoices, 'id', $id);
                return '$'.formatNumber(array_sum(array_column($result['payments'], 'payed'))
            -array_sum(array_column($result['payments'], 'cambio')));
            })->label('Pagado'),
            Column::callback(['rest'], function ($rest) {
                return '$'.formatNumber($rest);
            })->label('Pend.'),
            /* Column::checkbox()->label('Seleccionar'), */
           
        ];
    }
   
}
