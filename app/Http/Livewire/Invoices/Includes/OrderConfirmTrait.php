<?php

namespace App\Http\Livewire\Invoices\Includes;

use App\Models\Invoice;
use Illuminate\Support\Arr;

trait OrderConfirmTrait
{

    public $action, $invoice;
    public function tryPayInvoice()
    {
        $invoice = Invoice::whereId($this->form['id'])->first();
        $condition = ($this->form['condition'] == 'De Contado'|| $this->form['client']['id']==1) && $this->form['rest'] > 0;
        
        if ($condition && !auth()->user()->hasPermissionTo('Autorizar')) {
           
            $this->authorize('Fiar factura de contado o a Genérico', 'validateAuthorization','payInvoice','data=null','Autorizar');
        } else {
            $this->payInvoice();
        }
    }
    public function payInvoice()
    {
        $invoice = Invoice::find($this->form['id'])->load('seller',  'client', 'details.product.units', 'details.taxes', 'details.unit', 'payment.pdf', 'store.image', 'comprobante', 'pdf', 'place.preference');

        $this->validateData($invoice);
        if ($invoice->status !== 'waiting') {
            $this->emit('showAlert', 'Esta factura ya fue cobrada. Recargue la vista', 'warning');
            return;
        }
        if ($this->form['rest'] <= 0 && $this->form['status'] != 'entregado') {
           
            $this->form['condition'] = 'De Contado';
        } else {
           
        }
        $this->form['status'] = 'cerrada';
        $pagos = ['Efectivo' => $invoice->efectivo, 'Tarjeta' => $invoice->tarjeta, 'Transferencia' => $invoice->transferencia];
        $this->form['payway'] = array_search(max($pagos), $pagos);
        $payment = $invoice->payment;
        if ($invoice->image) {
            $payment->image()->create([
                'path' => $invoice->image->path
            ]);
        }
        $invoice->update(Arr::only($this->form, ['note', 'status', 'payway', 'contable_id']));
        $payment->update(Arr::only($this->form, ['efectivo', 'tarjeta', 'transferencia', 'payed', 'rest', 'cambio']));
        $invoice->update(['rest' => $payment->rest]);
        auth()->user()->payments()->save($payment);
        if ($payment->payed > 0) {
            setIncome($invoice, 'Ingreso por venta Factura Nº. ' . $invoice->number, $payment->payed);
        }
        if ($invoice->comprobante) {
            $this->setTaxes($invoice);
        }
        $this->setTransaction($invoice, $payment, $invoice->client);
        setPDFPath($invoice);

        $this->closeComprobante($invoice->comprobante, $invoice);
        $invoice = Invoice::whereId($this->form['id'])->with('seller', 'contable', 'client', 'details.product.units', 'details.taxes', 'details.unit', 'payment', 'store.image', 'payments.pdf', 'comprobante', 'pdf', 'place.preference')->first();
        $this->emit('showAlert', 'Factura cobrada exitosamente', 'success');
       
        if (auth()->user()->hasPermissionTo('Imprimir Facturas'))
            $this->emit('changeInvoice', $invoice);
        $this->emit('refreshLivewireDatatable');
        setDebt($invoice->client_id, 0);
    }
}
