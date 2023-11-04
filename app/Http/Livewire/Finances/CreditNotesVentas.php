<?php

namespace App\Http\Livewire\Finances;

use App\Http\Classes\NumberColumn;
use App\Models\Credit;
use App\Models\Invoice;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class CreditNotesVentas extends LivewireDatatable
{
    public $headTitle = 'Notas de Crédito de Ventas';
    public $padding = "px-2";
    public $hideable = 'select';
    public function builder()
    {
        $creditnotes=Credit::where('credits.place_id', getPlace()->id)
        ->join('invoices', 'invoices.id', '=', 'credits.creditable_id')
        ->join('clients', 'clients.id','=','invoices.client_id')
        ->select('credits.*', 'clients.name', 'invoices.number', 'invoices.id')
        ->where('credits.creditable_type', Invoice::class)
        ->orderBy('credits.id', 'desc');
        return $creditnotes;
    }

    public function columns()
    {
        return [
            Column::callback('clients.name', function ($provider) {
                return ellipsis($provider, 25);
            })->label('Cliente')->searchable(),
            Column::callback(['invoices.id', 'invoices.number'], function ($invoiceId, $invoiceNumber) {
                return '<a class="text-blue-600 hover:underline hover:text-blue-300" href="' . route('invoices.show', [$invoiceId, 'includeName' => 'showcredit', 'includeTitle' => 'Nota de Crédito']) . '">' . ltrim(substr($invoiceNumber, strpos($invoiceNumber, '-') + 1), '0') . '</a>';
            })->label('Fact.'),
            Column::name('credits.modified_ncf')->label('NCF Compra')->searchable(),
            Column::name('credits.ncf')->label('NCF NC')->searchable(),
            NumberColumn::name('credits.amount')->label('Monto')->formatear('money'),
            NumberColumn::name('credits.itbis')->label('ITBIS')->formatear('money'),
            DateColumn::name('credits.modified_at')->label('Fecha'),
            Column::name('comment')->label('Comentario')->searchable()->hide(),
        ];
    }
}
