<?php

namespace App\Http\Livewire\Clients;

use App\Jobs\CreatePDFJob;
use App\Models\Bank;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PayInvoices extends Component
{
    public $invoices, $banks, $confirmed = false, $total, $invoiceIds;
    public $amount, $payway = 'Efectivo', $ref, $bank_id;

    public function mount($invoices)
    {
        $store = auth()->user()->store;
        $this->banks = $store->banks()->select(DB::raw('CONCAT(bank_name," ",bank_number) AS name, id'))->pluck('name', 'banks.id');
        $this->invoicesIds=$invoices;
        $invoices = explode(',', $invoices);
        $invoices = Invoice::whereIn('id', $invoices)
        ->where('rest', '>', 0)
        ->with('payment', 'payments', 'details', 'client')
        ->orderBy('created_at')->get();
        $this->invoices = $invoices;
        $this->total=$invoices->sum('rest');
    }

    public function rules()
    {
        return [
            'amount' => 'required|numeric|min:' . $this->total,
            'payway' => 'required',

        ];
    }

    public function render()
    {
        return view('livewire.clients.pay-invoices');
    }
    public function payInvoice($invoice_id)
    {
        if ($this->payway == 'Transferencia') {
            $this->rules = array_merge($this->rules(), ['bank_id' => 'required']);
        }
        $this->validate();
        $invoice = Invoice::find($invoice_id);
        $this->amount -= $invoice->rest;
        $this->createPayment($invoice, $invoice->rest);
    }
    public function createPayment($invoice, $amount)
    {
        $efectivo = 0;
        $transferencia = 0;
        $tarjeta = 0;
        $cambio=0;
        if(count($this->invoices)==1){
            $cambio=$this->amount;
        }
        switch ($this->payway) {
            case 'Efectivo':
                $efectivo = $amount+$cambio;
                break;
            case 'Transferencia':
                $transferencia = $amount+$cambio;
                break;
            case 'Otra':
                $tarjeta = $amount+$cambio;
                break;
        }
        
        $data = [
            'ncf' => $invoice->payment->ncf,
            'amount' => $invoice->rest,
            'discount' => 0,
            'total' => $invoice->rest,
            'tax' =>  0,
            'payed' => $invoice->rest,
            'rest' =>  0,
            'forma' =>  'Cobro',
            'cambio' =>  $cambio,
            'efectivo' => $efectivo,
            'tarjeta' => $tarjeta,
            'contable_type' => User::class,
            'contable_id' => auth()->user()->id,
            'transferencia' => $transferencia,
        ];
        $payment=setPayment($data);
        $invoice->payments()->save($payment);
        $bank=Bank::find($this->bank_id);
        $invoice->client->payments()->save($payment);
        setPaymentTransaction($invoice, $payment, $invoice->client, $bank, $this->ref);
        $invoice->update([
            'rest' => 0
        ]);
        dispatch(new CreatePDFJob($invoice))->onConnection('sync');
        $this->emit('showAlert', 'Pago registrado exitosamente', 'success');
        $payment = $payment->load('payable.store', 'payer', 'payer', 'place.preference', 'payable.payment', 'contable');
        $this->emit('printPayment', $payment);
        $this->mount($this->invoicesIds);
    }
}
