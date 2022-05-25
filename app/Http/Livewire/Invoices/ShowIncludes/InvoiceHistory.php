<?php

namespace App\Http\Livewire\Invoices\ShowIncludes;

use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class InvoiceHistory extends LivewireDatatable
{
    public $invoice;
    public function builder()
    {
        return $this->invoice->payments()->with('pdf', 'image')->orderBy('id', 'desc');
    }

    public function columns()
    {
        $payments = $this->builder()->get()->toArray();
        return [
            DateColumn::name('created_at')->label('Fecha'),
            Column::callback('amount', function ($amount) {
                return '$' . formatNumber($amount);
            })->label('Monto'),
            Column::callback('payed', function ($payed) {
                return '$' . formatNumber($payed);
            })->label('Pagado'),
            Column::callback('rest', function ($rest) {
                return '$' . formatNumber($rest);
            })->label('Pendiente'),
            Column::callback(['efectivo', 'id'], function ($efectivo, $id) use ($payments) {
                $result = arrayFind($payments, 'id', $id);
                return " 
                <a href=".$result['pdf']['pathLetter']." download  >
                <span class='far fa-file-pdf text-red-600 text-xl mr-2'></span>
                <span class='far fa-download text-blue-600 text-lg'></span>
                 </a>";
            })->label('PDF'),
            Column::callback(['tarjeta', 'id'], function ($tarjeta, $id) use ($payments) {
                $result = arrayFind($payments, 'id', $id);
                if (!empty($result['image'])) {
                    return " 
                    <a href=" . $result['image']['path'] . " download='image.png'>
                    <span class='far fa-paperclip text-green-600 text-xl mr-2'></span>
                    <span class='far fa-download text-blue-600 text-lg'></span>
                     </a>";;
                } else {
                    return ' ';
                }
            })->label('Foto'),
        ];
    }
}
