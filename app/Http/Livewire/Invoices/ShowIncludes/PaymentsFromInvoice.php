<?php

namespace App\Http\Livewire\Invoices\ShowIncludes;

use Mediconesystems\LivewireDatatables\Action;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class PaymentsFromInvoice extends LivewireDatatable
{
    public $invoice;
    public $headTitle='Historial de pagos';
    public function builder()
    {
       
        return $this->invoice->payments()->with('payable')->orderBy('id','desc');
    }

    public function columns()
    {
        return [
            DateColumn::name('created_at')->label('Fecha'),
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
            Column::name('rest')->callback(['rest'], function ($rest) {
               
                return  "<span class='text-red-400 font-bold'>$". formatNumber($rest)."</span>";
            })->label('Resta'),
        ];
    }
    
}
