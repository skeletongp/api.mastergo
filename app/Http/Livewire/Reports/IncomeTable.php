<?php

namespace App\Http\Livewire\Reports;

use App\Http\Livewire\UniqueDateTrait;
use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class IncomeTable extends LivewireDatatable
{

    use UniqueDateTrait;
    
    public $headTitle = "Pagos a Facturas y cobros de Pendientes";
    public $reload = true;
    public $padding = "p-2";
    public function builder()
    {
        $place = auth()->user()->place;
        $payments = Payment::where('payments.place_id',$place->id)->where('payable_type', Invoice::class)
            ->join('invoices', 'invoices.id', '=', 'payments.payable_id')
            ->join('clients', 'clients.id', '=', 'payments.payer_id')
            ->join('moso_master.users', 'users.id', '=', 'payments.contable_id')
            ->where('payer_type', 'App\Models\Client')
            ->orderBy('payments.created_at', 'desc')
            ->select('payments.*', 'invoices.name as name', 'invoices.number', 'clients.name as client_name')
            ->orderBy('payments.updated_at', 'desc');
        return $payments;
    }

    public function columns()
    {
        return [
            DateColumn::name('created_at')->label('Fecha')->format('d/m/Y h:i A')->searchable()->filterable(),
            Column::callback(['clients.name', 'invoices.name'], function ($client, $name)  {
                return ellipsis($name?: $client, 20);
            })->label('Cliente')->searchable(),
            Column::callback('users.fullname', function ($cajero) {
                return ellipsis($cajero, 20);
            })->label('Cajero')->searchable(),
            Column::callback('invoices.number', function($number){
                return ltrim(substr($number, strpos($number, '-')+1), '0');
            })->label('Fact.')->searchable(),
            Column::callback(['efectivo', 'cambio'], function ($efectivo, $cambio) {
                $efectivo=$efectivo - $cambio;
                return '$' . formatNumber($efectivo>0?$efectivo:0);
            })->label('Efectivo')->searchable()->enableSummary(),
            Column::callback(['transferencia'], function ($transferencia) {
                return '$' . formatNumber($transferencia);
            })->label('Transf.')->searchable()->enableSummary(),
            Column::callback(['cambio'], function ($cambio) {
                return '$' . formatNumber($cambio);
            })->label('Cambio')->searchable()->enableSummary(),
            Column::callback(['payed', 'cambio'], function ($payed, $cambio) {
                return '$ <b>' . formatNumber($payed - $cambio) . '</b>';
            })->label('Pagado')->searchable()->enableSummary(),
            Column::callback(['rest'], function ($rest) {
                if ($rest > 0) {
                    return '<b class="text-red-400">$ ' . formatNumber($rest) . '</b>';
                }
                return '$' . formatNumber($rest);
            })->label('Resta')->searchable(),
           
        ];
    }
    public function summarize($column)
    {
        if ($this->perPage < 500) {
            return '';
        }
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
