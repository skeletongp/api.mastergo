<?php

namespace App\Http\Livewire\Invoices\ShowIncludes;

use App\Models\Bank;
use Livewire\WithFileUploads;

trait ShowPayments
{


    public $banks, $payment, $reference, $bank, $bank_id, $photo_path, $cheque;

    public function rules2(): array
    {
        return [
            'payment.efectivo' => 'required|numeric|min:0',
            'payment.tarjeta' => 'required|numeric|min:0',

        ];
    }
    public function validateData()
    {
        $rules = $this->rules2();
        if (auth()->user()->store->banks->count()) {
            # code...
            $rules = array_merge($rules, ['payment.transferencia' => 'required|numeric|min:0']);
        }
        if (!empty($this->payment['transferencia']) && $this->payment['transferencia'] > 0) {
            $rules = array_merge($rules, ['bank' => 'required']);
            $rules = array_merge($rules, ['reference' => 'required']);
        }
        if (!empty($this->payment['tarjeta']) && $this->payment['tarjeta'] > 0) {
            $rules = array_merge($rules, ['cheque' => 'required']);
        }
        $this->bank = Bank::find($this->bank_id);
        $this->validate($rules);
    }


    public function storePayment()
    {
        $this->validateData();
        $this->createPayment($this->invoice);
    }
    public function createPayment($invoice)
    {
        $subtotal = $this->invoice->payments()->orderBy('id', 'desc')->first()->rest;
        $discount = 0;
        $tax = 0;
        $total = $subtotal;
        $cambio = 0;
        $payed = array_sum($this->payment);
        $rest = 0;
        if ($total > $payed) {
            $rest = $total - $payed;
        } else {
            $cambio = $payed - $total;
        }

        $data = [
            'ncf' => $invoice->payment->ncf,
            'amount' => $subtotal,
            'discount' => $discount,
            'total' =>  $total,
            'tax' =>  $tax,
            'payed' => array_sum($this->payment),
            'rest' =>  $rest,
            'cambio' =>  $cambio,
            'efectivo' => $this->payment['efectivo'],
            'tarjeta' => $this->payment['tarjeta'],
            'transferencia' => empty($this->payment['transferencia']) ? 0 : $this->payment['transferencia'],
        ];
        if (optional($invoice->payment)->payed == 0) {
           $invoice->payment()->update($data);
        } else {
           $invoice->payment()->save(setPayment($data));

        }
        $payment=$invoice->payments()->orderBy('id','desc')->first();
        setIncome($invoice, 'Abono saldo Factura Nº. ' . $invoice->number, $payment->payed);
        $invoice->client->payments()->save($payment);
        setPaymentTransaction($invoice, $payment, $invoice->client, $this->bank, $this->reference);
        $invoice->update([
            'rest' => $invoice->rest - ($payment->payed - $payment->cambio)
        ]);
        if ($this->photo_path) {
            $invoice->image()->updateOrCreate(['imageable_id' => $invoice->id], [
                'path' => $this->photo_path
            ]);
            $payment->image()->create([
                'path' => $this->photo_path
            ]);
        }
        setPDFPath($invoice);
       
        $this->emit('showAlert', 'Pago registrado exitosamente', 'success');
        $this->emit('refreshLivewireDatatable');
        $this->reset('payment', 'bank_id', 'cheque', 'photo_path');
    }
    public function updatedCheque()
    {
        $this->reset('photo_path');
        $this->validate([
            'cheque' => 'image|max:2048'
        ]);
        $path = cloudinary()->upload($this->cheque->getRealPath())->getSecurePath();
        $this->photo_path = $path;
    }
}
