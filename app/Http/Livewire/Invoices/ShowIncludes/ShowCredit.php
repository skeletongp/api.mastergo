<?php

namespace App\Http\Livewire\Invoices\ShowIncludes;

use Carbon\Carbon;

trait ShowCredit
{
    public $modified_ncf, $modified_at, $comment, $creditComprobantes, $comprobanteCredit, $amount, $tax;

    public function createCreditnote()
    {
        if ($this->invoice->creditnote) {
            $this->emit('showAlert', 'La factura ya tiene una nota de crédito', 'error');
            return;
        }
        $this->validate([
            'modified_ncf' => 'required',
            'modified_at' => 'required',
            'comment' => 'required',
            'tax' => 'required|numeric|min:1',
            'amount' => 'required|numeric|min:1|gte:tax',
        ]);
        $this->closeComprobante();

        $this->invoice->creditnote()->create([
            'modified_ncf' => $this->modified_ncf,
            'modified_at' => $this->modified_at,
            'comment' => $this->comment,
            'amount' => $this->amount,
            'tax' => $this->tax,
            'place_id' => auth()->user()->place->id,
            'user_id' => auth()->user()->id,
            'comprobante_id' => $this->comprobanteCredit->id,
        ]);
        if ($this->invoice->rest >= $this->amount) {
            $payment = $this->invoice->payments->last();
            $payment->update([
                'rest' => $payment->rest - $this->amount,
                'cambio' => $payment->cambio + $this->amount,
            ]);
            $this->invoice->update([
                'rest' => $this->invoice->rest - $this->amount,
            ]);
        } else {
            $payment = $this->invoice->payments->last();
            $payment->update([
                'cambio' => $payment->cambio + $this->amount,
            ]);
        }
        $this->invoice->payment->update([
            'tax' => $this->invoice->payment->tax - $this->tax,
            'total' => $this->invoice->payment->total - $this->amount,
        ]);
        $this->setCreditTransaction($this->amount, $this->tax);
        $this->emit('showAlert', 'Nota de crédito creada con éxito', 'success');
        $this->render();
    }
    public function closeComprobante()
    {
        $store = auth()->user()->store;
        $this->comprobanteCredit = $store->comprobantes()->where('ncf', $this->modified_ncf)
            ->where('status', 'disponible')->orderBy('number')
            ->first();
        $this->comprobanteCredit->update([
            'status' => 'usado',
            'period' => Carbon::now()->format('Ym'),
            'user_id' => auth()->user()->id,
            'client_id' => $this->invoice->client_id,
            'place_id' => auth()->user()->place->id,

        ]);
    }

    public function initCreditnote()
    {
        $store = auth()->user()->store;
        $comprobante = $store->comprobantes()->where('prefix', 'B04')
            ->where('status', 'disponible')->orderBy('number')
            ->first();
        $this->modified_ncf = optional($comprobante)->ncf;
        $this->modified_at = date('Y-m-d');
        if ($this->invoice->creditnote) {
            $this->modified_ncf = $this->invoice->creditnote->modified_ncf;
            $this->modified_at = Carbon::parse($this->invoice->creditnote->modified_at)->format('Y-m-d');
            $this->comment = $this->invoice->creditnote->comment;
            $this->amount = $this->invoice->creditnote->amount;
            $this->tax = $this->invoice->creditnote->tax;
        }
    }
    public function printCreditNote()
    {
        $invoice = $this->invoice;
        $invoice = $invoice->load('seller', 'contable', 'client', 'details.product.units', 'details.taxes', 'details.unit', 'payment', 'store.image', 'payments.pdf', 'comprobante', 'pdf', 'place.preference');
        $this->emit('changeInvoice', $invoice, true, true);
    }
    public function setCreditTransaction($mount, $tax)
    {
        $place = auth()->user()->place;
        $desc_dev_ventas = $place->findCount('401-01');
        $cash = $place->findCount('101-01');
        $itbis = $place->findCount('203-01');

        setTransaction('Dev. Nota de crédito', $this->modified_ncf, $mount - $tax, $desc_dev_ventas, $cash, 'Editar Facturas');
        setTransaction('Dev. Nota de crédito ITBIS', $this->modified_ncf, $tax, $itbis, $cash, 'Editar Facturas');
    }
}
