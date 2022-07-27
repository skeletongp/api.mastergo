<?php

namespace App\Http\Livewire\Reports;

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
    use UniqueDateTrait;

    public function builder()
    {
        $place = auth()->user()->place;
        $invoices =
            Invoice::where('invoices.place_id', $place->id)
            ->orderBy('invoices.created_at', 'desc')
            ->join('payments', 'payments.payable_id', '=', 'invoices.id')
            ->where('payments.payable_type', '=', 'App\Models\Invoice')
            ->select('invoices.*', 'payments.id as payment_id', 'payments.efectivo as efectivo', 'payments.transferencia as transferencia', 'payments.cambio as cambio', 'payments.payed as payed','clients.name as client_name')
            ->join('clients', 'clients.id', '=', 'invoices.client_id')
            ->where('status', '=', 'cerrada');
        return $invoices;
    }

    public function columns()
    {
        return [
            Column::callback(['id','rest'], function ($id, $rest)  {
                if ($rest>0) {
                    return "  <a href=" . route('invoices.show', [$id, 'includeName' => 'showpayments', 'includeTitle' => 'Pagos']) .
                        "><span class='fas w-8 text-center fa-hand-holding-usd'></span> </a>";
                } else {
                    return "  <a href=" . route('invoices.show', $id) . "><span class='fas w-8 text-center fa-eye'></span> </a>";
                }
            })->label(''),
            Column::callback(['number'], function ($number) {
                $number = ltrim(substr($number, strpos($number, '-') + 1), '0');
                return $number;
            })->label('Nro.')->searchable(),
            DateColumn::name('created_at')->label('Fecha')->format('d/m/Y h:i A')->searchable()->filterable(),
            Column::callback(['clients.name', 'name'], function ($client, $name) {

                return ellipsis($name ?: $client, 20);
            })->label('Cliente')->searchable(),
            Column::name('condition')->label('Condición')->filterable([
                'De Contado', 'Contra Entrega', '1 A 15 Días', '16 A 30 Días', '31 A 45 Dïas'
            ]),
            Column::callback(['payments.total','payments.payed','payments.cambio'], function ($total, $payed, $cambio)  {
                
                return '$ <b>' . formatNumber($total).'</b>';
            })->label('Monto'),

            Column::callback(['payments.efectivo', 'payments.cambio'], function ($efectivo, $cambio)  {
                $efectivo=$efectivo-$cambio;
                return '$' . formatNumber($efectivo>0?$efectivo:0);
            })->label('Efectivo'),
            Column::callback(['payments.transferencia', 'payments.cambio'], function ($transferencia, $cambio)  {
                return '$' . formatNumber($transferencia);
            })->label('Transf.'),
            Column::callback(['payments.payed', 'payments.cambio'], function ($payed, $cambio)  {
                return '$' . formatNumber($payed-$cambio);
            })->label('Pagado'),

            Column::callback(['rest'], function ($rest) {
                return '$' . formatNumber($rest);
            })->label('Pend.'),
            /* Column::checkbox()->label('Seleccionar'), */

        ];
    }
}
