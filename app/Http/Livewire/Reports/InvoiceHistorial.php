<?php

namespace App\Http\Livewire\Reports;

use App\Http\Classes\NumberColumn ;
use App\Http\Livewire\UniqueDateTrait;
use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class InvoiceHistorial extends LivewireDatatable
{

    public $headTitle = "Historial de facturas";
    public $padding = "px-2";
    public $hideable='select';
    use UniqueDateTrait;

    public function builder()
    {
       
        $place = auth()->user()->place;
        $invoices =
            Payment::where('invoices.place_id', $place->id)
            ->orderBy('invoices.created_at', 'desc')
            ->join('invoices', 'payments.payable_id', '=', 'invoices.id')
            ->where('payments.payable_type', '=', 'App\Models\Invoice')
            ->leftjoin('comprobantes','invoices.comprobante_id','=','comprobantes.id')
            ->join('clients', 'clients.id', '=', 'invoices.client_id')
            ->where('invoices.status', '=', 'cerrada')
            ->groupBy('invoices.id');
        return $invoices;
    }
    
    public function bindClasses(){
       
    }
    public function columns()
    {
        return [
            Column::callback(['invoices.id', 'invoices.rest'], function ($id, $rest) {
                if ($rest > 0) {
                    return "  <a href=" . route('invoices.show', [$id, 'includeName' => 'showpayments', 'includeTitle' => 'Pagos']) .
                        "><span class='fas w-8 text-center fa-hand-holding-usd'></span> </a>";
                } else {
                    return "  <a href=" . route('invoices.show', $id) . "><span class='fas w-8 text-center fa-eye'></span> </a>";
                }
            })->label(''),
            Column::callback(['invoices.number'], function ($number) {
                $number = ltrim(substr($number, strpos($number, '-') + 1), '0');
                return $number;
            })->label('Nro.')->searchable(),
            DateColumn::name('invoices.created_at')->label('Fecha')->format('d/m/Y h:i A')->searchable()->filterable(),
            Column::callback(['clients.name', 'invoices.name'], function ($client, $name) {
                return ellipsis($name ?: $client, 20);
            })->label('Cliente')->searchable(),
            Column::name('invoices.condition')->label('Condición')->filterable([
                'De Contado', 'Contra Entrega', '1 A 15 Días', '16 A 30 Días', '31 A 45 Dïas'
            ]),
            NumberColumn::raw('total')->label('Total')->searchable()->formatear('money')->enableSummary(),
            NumberColumn::raw('SUM(efectivo) AS efectivo')->label('Efectivo')->formatear('money')->enableSummary(),
            NumberColumn::raw('SUM(transferencia) AS transferencia')->label('Transf.')->formatear('money')->enableSummary(),
            NumberColumn::raw('SUM(payed) AS payed')->label('Pagado')->formatear('money')->enableSummary(),
            NumberColumn::raw('SUM(cambio) AS cambio')->label('cambio')->formatear('money')->enableSummary(),
            NumberColumn::raw('invoices.rest')->label('Resta')->formatear('money')->enableSummary(),
            Column::callback(['invoices.type', 'comprobantes.type'], function ($prefix, $type) {
                return $type?:'DOCUMENTO CONDUCE';
            })->label('TIPO')->searchable()->filterable([
                'B00' => 'CONDUCE',
                'B01' => 'CRÉDITO FISCAL',
                'B02' => 'CONSUMO FINAL',
                'B14' => 'RÉGIMEN ESPECIAL',
                'B15' => 'GUBERNAMENTAL',
            ])->hide(),
            /* Column::checkbox()->label('Seleccionar'), */

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
