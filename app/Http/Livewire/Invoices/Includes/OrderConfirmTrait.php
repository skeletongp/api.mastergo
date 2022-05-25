<?php

namespace App\Http\Livewire\Invoices\Includes;

use App\Models\Invoice;
use Illuminate\Support\Arr;

trait OrderConfirmTrait
{

    public $action;
    public function tryPayInvoice()
    {
        $invoice = Invoice::find($this->form['id']);
        $condition = $invoice->condition == 'De Contado' && $this->form['rest'] > 0;
        if ($condition && !auth()->user()->hasPermissionTo('Autorizar')) {
            $this->action = 'payInvoice';
            $this->emit('openAuthorize');
        } else {
            $this->payInvoice();
        }
    }
    public function payInvoice()
    {
        $invoice = Invoice::find($this->form['id']);
        $this->validateData($invoice);
        if ($invoice->status !== 'waiting') {
            $this->emit('showAlert', 'Esta factura ya fue cobrada. Recargue la vista', 'warning');
            return;
        }
        if ($this->form['rest'] <= 0 && $this->form['status'] != 'entregado') {
            $this->form['status'] = 'pagado';
            $this->form['condition'] = 'De Contado';
        } else {
            $this->form['status'] = 'adeudado';
        }
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
        $this->emit('refreshLivewireDatatable');
        return redirect()->route('invoices.index');
    }
}
