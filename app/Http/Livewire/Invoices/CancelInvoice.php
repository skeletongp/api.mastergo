<?php

namespace App\Http\Livewire\Invoices;

use App\Http\Traits\Livewire\Confirm;
use App\Models\Invoice;
use Livewire\Component;

class CancelInvoice extends Component
{
    use Confirm;
    public $invoice_id;
    protected $listeners = ['cancelInvoice', 'validateAuthorization'];

    public function render()
    {
        return view('livewire.invoices.cancel-invoice');
    }

    public function cancelInvoice()
    {
        $invoice = Invoice::whereId($this->invoice_id)->with('payments', 'details.product', 'comprobante', 'payments', 'payments', 'seller', 'contable', 'client')->first();
        $comprobante = $invoice->comprobante;
        $details = $invoice->details;

        if ($comprobante && $comprobante->status == 'reportado') {
            $this->emit('showAlert', 'Esta factura no puede anularse porque ya está reportada', 'warning');
            return false;
        }
        if ($invoice->day !== date('Y-m-d')) {
            $this->emit('showAlert', 'No puede anularse factura porque no es de hoy', 'warning');
            return false;
        }
        if ($comprobante) {
            $this->cancelComprobante($comprobante);
        }
        $this->deleteDetails($details);
        $this->deleteTaxes($invoice);
        $this->deletePayments($invoice);
        $invoice->update(['status' => 'anulada']);
        $this->emit('showAlert', 'Factura anulada correctamente', 'success');
        $this->emit('refreshLivewireDatatable');
    }
    public function cancelComprobante($comprobante)
    {
        $comprobante->update([
            'status' => 'disponible',
            'period' => NULL,
            'user_id' => NULL,
            'place_id' => NULL,
            'client_id' => NULL,
        ]);
    }
    public function deleteTaxes($invoice)
    {
        $invoice->taxes()->sync([]);
    }
    public function deleteDetails($details)
    {
        foreach ($details as $det) {
            $this->restoreProducto($det->product, $det->unit_id, $det->cant);
            $det->delete();
        }
    }
    public function restoreProducto($product, $unit_id, $cant)
    {
        $unit = $product->units()->where('units.id', $unit_id)->first()->pivot;
        $unit->stock = $unit->stock + $cant;
        $unit->save();
    }
    public function deletePayments($invoice)
    {
        $place = $invoice->place;
        $payments = $invoice->payments;
        $payment = $invoice->payment;
        $ref = $invoice->comprobante ? $invoice->comprobante->ncf : $invoice->number;
        $rp = $invoice->venta / ($invoice->payment->amount - $invoice->payment->discount);
        $rs = $invoice->venta_service / ($invoice->payment->amount - $invoice->payment->discount);
        $devVentas = $place->findCount('401-01');
        $ingresos_service = $place->findCount('400-02');
        $client=$invoice->client;
        $rTax=$payment->tax/($payment->amount-$payment->discount);
        $caja = $place->findCount('100-01');
        $itbisCount = $place->findCount('203-01');
        setTransaction('Reg. Dev. ventas de productos', $ref, $payments->sum('payed') * (1-$rTax), $devVentas, $caja, 'Borrar Facturas');
       
        setTransaction('Reg.  Dev. de productos a Crédito', $ref, $invoice->rest * (1-$rTax),  $devVentas, $client->contable()->first(), 'Cobrar Facturas');
      
        setTransaction('Reg. reversión de ITBIS x Pagar', $ref,  $payment->tax, $devVentas, $itbisCount, 'Borrar Facturas');
        setTransaction('Reg. reversión costo de ventas', $ref, $invoice->gasto, $place->inventario(), $place->ventas(), 'Cobrar Facturas');
        foreach ($payments as $pay) {
            $pay->delete();
        }
    }
}
