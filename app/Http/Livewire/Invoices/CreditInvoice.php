<?php

namespace App\Http\Livewire\Invoices;

use App\Http\Classes\Column;
use App\Models\Credit;
use App\Models\Invoice;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class CreditInvoice extends LivewireDatatable
{
    public $headTitle='Notas de Crédito';
    public $invoice;
    public function builder()
    {
        $place = auth()->user()->place;
        $credits = Credit::where("creditable_type", Invoice::class)
            ->where("place_id", $place->id)
            ->where('creditable_id', $this->invoice->id);
        return $credits;
    }

    public function columns()
    {
        return [
            Column::name('comment')->label("Comentario"),
            Column::name('modified_ncf')->label("NCF Mod."),
            Column::name('ncf')->label("NCF"),
            Column::name('amount')->label("Monto"),
            Column::name('ITBIS')->label("ITBIS"),
            Column::name('modified_at')->label("Fecha"),

        ];
    }

    public function printCreditNote()
    {
        $invoice = $this->invoice;
        $invoice = $invoice->load('seller', 'contable', 'client', 'details.product.units', 'details.itbises', 'details.unit', 'payment', 'store.image', 'payments.pdf', 'comprobante', 'pdf', 'place.preference');
        $this->emit('changeInvoice', $invoice, true, true);
    }
}
