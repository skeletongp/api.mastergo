<?php

namespace App\Http\Livewire\Finances;

use App\Http\Classes\NumberColumn;
use App\Models\Creditnote;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class CreditNotes extends LivewireDatatable
{
    public $headTitle='Notas de Crédito';
    public $padding = "px-2";
    public $hideable='select';
    public function builder()
    {
        $creditnotes=Creditnote::where('creditnotes.place_id', getPlace()->id)
            ->join('comprobantes', 'comprobantes.id', '=', 'creditnotes.comprobante_id')
            ->join('clients', 'clients.id', '=', 'comprobantes.client_id')
            ->join('invoices', 'invoices.id', '=', 'creditnotes.invoice_id')
            ->join('comprobantes as comps', 'comps.id', '=', 'invoices.comprobante_id')
            ->select('creditnotes.*', 'comprobantes.ncf', 'comprobantes.prefix', 'comprobantes.status', 'clients.name', 'invoices.number as invoice_number')
            ->orderBy('creditnotes.id', 'desc');
            return $creditnotes;
    }

    public function columns()
    {
        return [
            Column::callback('clients.name', function($client){
                return ellipsis($client, 25);
            })->label('Cliente')->searchable(),
            Column::callback(['invoices.id', 'invoices.number'], function ($invoiceId, $invoiceNumber) {
                return '<a href="' . route('invoices.show', $invoiceId) . '">' . ltrim(substr($invoiceNumber, strpos($invoiceNumber, '-') + 1), '0') . '</a>';
            })->label('Fact.'),
            Column::name('comprobantes.ncf')->label('NCF')->searchable(),
            Column::name('comps.ncf')->label('NCF Fact.')->searchable(),
            NumberColumn::name('creditnotes.amount')->label('Monto')->formatear('money'),
            NumberColumn::name('creditnotes.tax')->label('ITBIS')->formatear('money'),
            DateColumn::name('creditnotes.modified_at')->label('Fecha de Uso'),
            Column::name('comment')->label('Comentario')->searchable()->hide(),

        ];
    }
}