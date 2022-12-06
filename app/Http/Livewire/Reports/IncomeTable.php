<?php

namespace App\Http\Livewire\Reports;

use App\Http\Livewire\UniqueDateTrait;
use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Classes\Column;
use App\Http\Classes\NumberColumn;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class IncomeTable extends LivewireDatatable
{

    
    public $headTitle = "Facturación y cobros pendientes";
    public $reload = true;
    public $padding = "p-2";
    public $hideable='select';
    public function builder()
    {
        $place = auth()->user()->place;
        $payments = Payment::where('payments.place_id',$place->id)->where('payable_type', Invoice::class)
            ->join('invoices', 'invoices.id', '=', 'payments.payable_id')
            ->join('clients', 'clients.id', '=', 'payments.payer_id')
            ->join('moso_master.users', 'users.id', '=', 'payments.contable_id')
            ->where('payer_type', 'App\Models\Client')
            ->select('payments.*', 'invoices.name as name', 'invoices.number', 'clients.name as client_name')
           ;
           
        return $payments;
    }

    public function columns()
    {
        return [
            DateColumn::name('created_at')->label('Fecha')->format('d/m/Y h:i A')->searchable()->filterable()->defaultSort('desc'),
            Column::callback(['clients.name', 'invoices.name'], function ($client, $name)  {
                return ellipsis($name?: $client, 20);
            })->label('Cliente')->searchable(),
            Column::callback('users.fullname', function ($cajero) {
                return ellipsis($cajero, 20);
            })->label('Cajero')->searchable()->hide(),
            Column::callback(['invoices.number','invoices.id'], function($number, $id){
                return "<a class='text-blue-500 hover:underline hover:font-bold' href='".route('invoices.show', $id)."'>".ltrim(substr($number, strpos($number, '-')+1), '0')."</a>";
            })->label('Fact.')->searchable(),
            NumberColumn::name('total')->label('Monto')->formatear('money')->searchable()->filterable(),
            NumberColumn::raw('(if(efectivo-cambio>0,efectivo-cambio,0)) AS efectivo')->label('Efectivo')->formatear('money')->searchable()->filterable()->enableSummary(),
            NumberColumn::name('transferencia')->label('Transf.')->formatear('money')->searchable()->filterable()->enableSummary(),
            NumberColumn::name('tarjeta')->label('Otros')->formatear('money')->searchable()->filterable()->hide()->enableSummary(),
            NumberColumn::name('rest')->label('Resta')->formatear('money')->searchable()->filterable(),
            NumberColumn::raw('(payed) AS payed')->label('Pagado')->formatear('money')->searchable()->filterable()->hide()->enableSummary(),
            NumberColumn::name('cambio')->label('Cambio')->formatear('money')->searchable()->filterable()->enableSummary(),
            NumberColumn::raw('(payed-cambio) AS final')->label('Total')->formatear('money')->searchable()->filterable()->hide()->enableSummary(),
         
        ];
    }
    public function summarize($column)
    {
        
        $results = json_decode(json_encode($this->results->items()), true);
        foreach ($results as $key => $value) {
            $val = json_decode(json_encode($value), true);
            $results[$key][$column] = preg_replace("/[^0-9 .]/", '', $val[$column]);
        }
        try {

            return "<h1 class='font-bold text-right'>" . '$' . formatNumber(array_sum(array_column($results, $column))) . "</h1>";;
        } catch (\TypeError $e) {
            return '';
        }
    }
    
}
