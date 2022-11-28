<?php

namespace App\Http\Livewire\Comprobantes;

use App\Models\Comprobante;
use Livewire\Component;

class AnulateComprobante extends Component
{
    public $comprobante_id, $invoice_id, $motivo;
    public $comprobante, $invoice;
    public $anulateFactura = false;
    public $gastoGeneral, $gastoTerminado;

    protected $listeners = ['modalOpened'];
    public function render()
    {
        return view('livewire.comprobantes.anulate-comprobante');
    }

    public function modalOpened()
    {
        $this->comprobante = Comprobante::find($this->comprobante_id);
        $this->invoice = $this->comprobante->invoice->load('payments', 'client', 'payment');
    }

    public function anulate()
    {
        $this->validate([
            'motivo' => 'required'
        ]);

        if ($this->anulateFactura) {
            $this->cancelInvoice();
        } else {
            $this->invoice->update([
                'type' => 'B00'
            ]);
        }
        $this->comprobante->update([
            'status' => 'anulado',
            'motivo' => $this->motivo
        ]);
        $this->emit('showAlert', 'Comprobante anulado correctamente', 'success');
        $this->emit('refreshLivewireDatatable');
    }

    public function cancelInvoice()
    {
        $invoice = $this->invoice->load('payments', 'details.product', 'details.taxes', 'comprobante', 'payments', 'payments', 'seller', 'contable', 'client');
        $details = $invoice->details;

        $this->rTax = $invoice->taxes()->sum('rate');
        $this->deleteDetails($details);
        $this->deleteTaxes($invoice);
        $this->deletePayments($invoice);
        $invoice->update([
            'status' => 'anulada'
        ]);
        $this->emit('showAlert', 'Factura ajustada correctamente', 'success');
        $this->emit('refreshLivewireDatatable');
    }

    public function deleteTaxes($invoice)
    {
        $invoice->taxes()->sync([]);
    }
    public function deleteDetails($details)
    {
        $id = $details->first()->id;
        foreach ($details as $det) {
            $this->restoreProducto($det->product, $det->unit_id, $det->cant);
            if ($id != $det->id) {
                $det->delete();
            } else {
                $this->rTax = $det->taxes()->sum('rate');
            }
        }
    }
    public function restoreProducto($product, $unit_id, $cant)
    {
        $unit = $product->units()->where('units.id', $unit_id)->first()->pivot;
        $unit->stock = $unit->stock + $cant;
        $unit->save();
    }
    public function getGastos($invoice)
    {
        $details = $invoice->details;
        foreach ($details as $det) {
            if ($det->product->origin == 'Comprado') {
                $this->gastoGeneral += $det->cost;
            } else if ($det->product->origin == 'Procesado') {
                $this->gastoTerminado += $det->cost;
            }
        }
    }
    public function deletePayments($invoice)
    {
        $place = $invoice->place;
        $this->getGastos($invoice);
        $payments = $invoice->payments;
        $payment = $invoice->payment;
        $ref = $invoice->comprobante ? $invoice->comprobante->ncf : $invoice->number;
        $rp = $invoice->venta / ($invoice->payment->amount - $invoice->payment->discount);
        $rs = $invoice->venta_service / ($invoice->payment->amount - $invoice->payment->discount);
        $devVentas = $place->findCount('401-01');
        $ingresos_service = $place->findCount('400-02');
        $client = $invoice->client;
        $rTax = $this->rTax;
        $caja = $place->findCount('100-01');
        $itbisCount = $place->findCount('203-01');
        setTransaction('Reg. Dev. ventas de productos', $ref, $payments->sum('payed') * (1 - $rTax), $devVentas, $caja, 'Borrar Facturas');
        setTransaction('Reg.  Dev. de productos a Crédito', $ref, $invoice->rest * (1 - $rTax),  $devVentas, $client->contable()->first(), 'Cobrar Facturas');
        setTransaction('Reg. reversión de ITBIS x Pagar', $ref,  $payment->tax, $devVentas, $itbisCount, 'Borrar Facturas');
        setTransaction('Reg. reversión costo de inventario general', $ref, $this->gastoGeneral, $place->inventario(), $place->ventas(), 'Cobrar Facturas');
        setTransaction('Reg. reversión costo de producto terminado', $ref, $this->gastoTerminado, $place->producto_terminado(), $place->ventas(), 'Cobrar Facturas');
        foreach ($payments as $pay) {
            $pay->delete();
        }
    }
}
