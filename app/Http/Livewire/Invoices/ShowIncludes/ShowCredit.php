<?php

namespace App\Http\Livewire\Invoices\ShowIncludes;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait ShowCredit
{
    public $creditComprobantes, $comprobanteCredit;
    public $credit;

    public function modalOpened()
    {
    }

    public function createCreditnote()
    {
        $this->validate([
            'credit.modified_ncf' => 'required',
            'credit.modified_at' => 'required',
            'credit.comment' => 'required',
            'credit.itbis' => 'required|numeric|min:0',
            'credit.propina' => 'required|numeric|min:0',
            'credit.selectivo' => 'required|numeric|min:0',
            'credit.amount' => 'required|numeric|min:1|lte:invoice.payment.total|gte:credit.itbis|gte:credit.propina|gte:credit.selectivo',
        ]);
        DB::beginTransaction();
        try {
            $this->closeComprobante();
            $totalCredits = $this->invoice->credits()->sum('amount');
            $this->invoice->credits()->create(
                $this->credit
            );
            $payment = $this->invoice->payments->last();
            if ($totalCredits + $this->credit["amount"] > $payment->total) {
                $this->emit('showAlert', 'El monto sobrepasa la venta', 'arror', 4500);
                return;
            }
            if ($this->invoice->rest >= $this->credit['amount']) {
                $payment->update([
                    'rest' => $payment->rest - $this->credit['amount'],

                ]);
                $this->invoice->update([
                    'rest' => $this->invoice->rest - $this->credit['amount'],
                ]);
            } else {
                $payment->update([
                    'cambio' => $payment->cambio + $this->credit['amount'],
                ]);
            }

            $this->setCreditTransaction($this->credit['amount'], $this->credit['itbis']);
            DB::commit();
            $this->initCreditnote();
            $this->emit('showAlert', 'Nota de crédito creada con éxito', 'success');
            $this->emit('refreshLivewireDatatable');
            $this->render();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    public function closeComprobante()
    {
        $store = auth()->user()->store;
        $this->comprobanteCredit = $store->comprobantes()->where('ncf', $this->credit['ncf'])
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
        $this->invoice = $this->invoice->load('credits');
        $comprobante = $store->comprobantes()->where('prefix', 'B04')
            ->where('status', 'disponible')->orderBy('number')
            ->first();
        if ($this->invoice->comprobante) {
            $this->credit = [
                "itbis" => 0,
                "selectivo" => 0,
                "propina" => 0,
                "creditable_type" => Invoice::class,
                "creditable_id" => $this->invoice->id,
                "user_id" => auth()->user()->id,
                'place_id' => auth()->user()->place->id,
                "ncf" => optional($comprobante)->ncf,
                "modified_at" => Carbon::now()->format('Y-m-d'),
                "modified_ncf" => $this->invoice->comprobante->ncf
            ];
        }
    }
    public function printCreditNote()
    {
        $invoice = $this->invoice;
        $invoice = $invoice->load('seller', 'contable', 'client', 'details.product.units', 'details.taxes', 'details.unit', 'payment', 'store.image', 'payments.pdf', 'comprobante', 'pdf', 'place.preference');
        $this->emit('changeInvoice', $invoice, true, true);
    }
    public function setCreditTransaction($mount, $itbis)
    {
        $place = auth()->user()->place;
        $desc_dev_ventas = $place->findCount('401-01');
        $cash = $place->findCount('101-01');
        $itbis = $place->findCount('203-01');
        $client = $this->invoice->client->contable;
        if ($this->invoice->rest >= $mount) {
            setTransaction('Dev. Nota de crédito', $this->credit['modified_ncf'], $mount - $this->credit['itbis'], $desc_dev_ventas, $client, 'Editar Facturas');
            setTransaction('Dev. Nota de crédito ITBIS', $this->credit['modified_ncf'], $this->credit['itbis'], $itbis, $client, 'Editar Facturas');
        } else {
            setTransaction('Dev. Nota de crédito', $this->credit['modified_ncf'], $mount - $this->credit['itbis'], $desc_dev_ventas, $cash, 'Editar Facturas');
            setTransaction('Dev. Nota de crédito ITBIS', $this->credit['modified_ncf'], $this->credit['itbis'], $itbis, $cash, 'Editar Facturas');
        }
    }
}
