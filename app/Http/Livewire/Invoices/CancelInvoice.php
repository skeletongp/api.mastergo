<?php

namespace App\Http\Livewire\Invoices;

use App\Http\Traits\Livewire\Confirm;
use App\Models\Invoice;
use Livewire\Component;

class CancelInvoice extends Component
{
    use Confirm;
    public $invoice_id, $rTax = 0;
    protected $listeners = ['cancelInvoice', 'validateAuthorization'];
    public $gastoGeneral, $gastoTerminado;
    
    public function render()
    {
        return view('livewire.invoices.cancel-invoice');
    }

    public function cancelInvoice()
    {
        $invoice = Invoice::whereId($this->invoice_id)->with('payments', 'details.product', 'details.taxes', 'comprobante', 'payments', 'payments', 'seller', 'contable', 'client')->first();
        $comprobante = $invoice->comprobante;
        $details = $invoice->details;

        if ($comprobante && $comprobante->status == 'reportado') {
            $this->emit('showAlert', 'Esta factura no puede anularse porque ya está reportada', 'warning');
            return false;
        }

        /* if ($comprobante) {
            $this->cancelComprobante($comprobante);
        } */
        $this->rTax = $invoice->taxes()->sum('rate');
        $this->deleteDetails($details);
        $this->deleteTaxes($invoice);
        $this->deletePayments($invoice);
        $this->emit('showAlert', 'Factura ajustada correctamente', 'success');
        $this->emit('refreshLivewireDatatable');
        /* 
        $invoice->update(['status' => 'anulada']);*/
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
    public function getGastos($invoice){
        $details=$invoice->details;
        foreach ($details as $det) {
            if ($det->product->origin=='Comprado') {
                $this->gastoGeneral += $det->cost;
            } else if ($det->product->origin=='Procesado' ) {
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
        $price = 50 / (1 + $rTax);
        $itbisCount = $place->findCount('203-01');
        $newPay = setPayment(
            [
                'ncf' => optional($invoice->comprobante)->ncf,
                'amount' => $price,
                'discount' => 0,
                'total' => $price * (1 + $rTax),
                'tax' =>  $price * $rTax,
                'payed' => 50,
                'rest' => 0,
                'cambio' =>  0,
                'efectivo' => 50,
                'tarjeta' => 0,
                'transferencia' => 0,
                'forma' => 'Contado'
            ]
        );
        $invoice->payment()->save($newPay);
        $invoice->client->payments()->save($newPay);
        setTransaction('Reg.$42.37 base ajuste Fct.', $ref, $price, $place->cash(), $devVentas, 'Cobrar Facturas');
        setTransaction('Reg. $7.63 base ajuste Fct.', $ref, $price * $rTax, $itbisCount, $devVentas, 'Cobrar Facturas');
        $this->setNewDet($invoice->details->first(), $rTax);
    }
    function setNewDet($det, $rTax)
    {
        $price = 50 / (1 + $rTax);
        $det->cant = 1;
        $det->subtotal = $price;
        $det->total = $price;
        $det->cost = $price * (1 + $rTax);
        $det->taxTotal = $price * $rTax;
        $det->discount = 0;
        $det->discount_rate = 0;
        $det->utility = 0;
        $det->price_type = 'detalle';
        $det->price = $price;
        $det->save();
        $det->detailable()->update(['rest' => 0]);
    }
}
