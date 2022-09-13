<?php

namespace App\Http\Livewire\Clients;

use App\Http\Classes\NumberColumn as ClassesNumberColumn;
use App\Models\Payment;
use App\Models\Transaction;
use Carbon\Carbon;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class ClientTransactions extends LivewireDatatable
{
    public $client;
    public $headTitle = "Transacciones Realizadas";
    public $padding = "px-2";
    public $hideResults = true;
    public function builder()
    {
        $payments = 
       Payment::where('payer_id', $this->client->id)
       ->leftjoin('invoices', 'invoices.id', 'payments.payable_id')
       ->where('payments.payable_type', 'App\Models\Invoice')
       ;
        return $payments;
    }

    public function columns()
    {
        return [
            Column::name('invoices.number')->label('Factura'),
            ClassesNumberColumn::name('total')->label('Monto')->formatear('money'),
            ClassesNumberColumn::raw('payed-cambio AS payed')->label('Pago')->formatear('money'),
            ClassesNumberColumn::name('rest')->label('Resta')->formatear('money'),
           /*  DateColumn::name('created_at')->label('Hora')->format('d/m/Y h:i A'),
            Column::callback('concepto', function ($concepto) {
                return ellipsis($concepto, 50);
            })->label('Concepto')->searchable()->filterable(
              
            ),
            Column::callback('income', function ($income) {
                return '$' . formatNumber($income);
            })->label('Monto'),
 */


        ];
    }
}
