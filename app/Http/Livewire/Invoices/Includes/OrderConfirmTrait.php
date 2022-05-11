<?php

namespace App\Http\Livewire\Invoices\Includes;

use App\Models\Invoice;
use Illuminate\Support\Arr;

trait OrderConfirmTrait{
    public function payInvoice()
    {
        $invoice = Invoice::find($this->form['id']);
        $this->validateData($invoice);
        if ($invoice->status !== 'waiting') {
            $this->emit('showAlert', 'Esta factura ya fue cobrada. Recargue la vista', 'warning');
            return;
        }
        if ($invoice->condition == 'De Contado' && $this->form['rest'] > 0) {
            $this->emit('showAlert', 'Está factura debe ser saldada', 'warning');
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
        $invoice->update(Arr::only($this->form, ['note', 'status', 'payway', 'contable_id']));
        $payment->update(Arr::only($this->form, ['efectivo', 'tarjeta', 'transferencia', 'payed', 'rest', 'cambio']));
        if ($payment->rest > 0) {
            setContable($invoice->client, '101');
        }
        setIncome($invoice, 'Ingreso por venta Factura Nº. ' . $invoice->number, $payment->payed);
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