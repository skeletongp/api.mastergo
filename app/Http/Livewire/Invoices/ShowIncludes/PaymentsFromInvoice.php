<?php

namespace App\Http\Livewire\Invoices\ShowIncludes;

use Mediconesystems\LivewireDatatables\Action;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class PaymentsFromInvoice extends LivewireDatatable
{
    public $invoice;
    public $hideable="select";
    public $headTitle='Historial de pagos';
    public function builder()
    {
       
        return $this->invoice->payments()->with('payable.store', 'payer', 'payer', 'place.preference', 'contable')->orderBy('id','desc');
    }

    public function columns()
    {
        
        return [
            DateColumn::name('created_at')->label('Fecha')->hide(),
            Column::name('total')->callback(['total'], function ($total) {
                return '$' . formatNumber($total);
            })->label('Monto'),
            Column::name('efectivo')->callback(['efectivo'], function ($efectivo) {
                return '$' . formatNumber($efectivo);
            })->label('Efectivo'),
            Column::name('transferencia')->callback(['transferencia'], function ($transferencia) {
                return '$' . formatNumber($transferencia);
            })->label('Transf.'),
            Column::name('tarjeta')->callback(['tarjeta'], function ($tarjeta) {
                return '$' . formatNumber($tarjeta);
            })->label('Otros'),

            Column::name('payed')->callback(['payed'], function ($payed) {
                return "<b>$". formatNumber($payed)."</b>";
            })->label('Pagado'),
            Column::callback(['rest'], function ($rest) {
                return  "<span class='text-red-400 font-bold'>$". formatNumber($rest)."</span>";
            })->label('Resta'),
            Column::callback(['cambio'], function ($cambio) {
                return  "<span class='text-blue-600 font-bold'>$". formatNumber($cambio)."</span>";
            })->label('Cambio'),
            Column::callback(['payer_id', 'id'], function ($payer, $id)  {
                return  "<span class='far fa-print cursor-pointer' wire:click='print($id)'> </span>";
            })->label('Print')->contentAlignCenter(),
        ];
    }
    public function print($id)
    {
        $payments=$this->builder()->get()->toArray();
        $result=arrayFind($payments, 'id', $id);
        $this->emit('printPayment', $result);
    }
    
}
