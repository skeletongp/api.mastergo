<?php

namespace App\Http\Livewire\Invoices;

use App\Models\Invoice;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class InvoiceList extends LivewireDatatable
{

    public $hidePagination=true;

    public function builder()
    {
        $invoices = auth()->user()->place->invoices()->orderBy('updated_at', 'desc')
            ->where('status', '!=', 'waiting')->with('pdfs', 'payment');
        return $invoices;
    }

    public function columns()
    {
        return [
            Column::name('number')->label('Orden'),
            Column::name('payment.total')->callback('payment.total:sum', function ($total) {
                return '$' . formatNumber($total);
            })->label('Monto'),
            Column::name('id')->label('Ver')->view('livewire.invoices.includes.setPDF'),
        ];
    }
    
}
