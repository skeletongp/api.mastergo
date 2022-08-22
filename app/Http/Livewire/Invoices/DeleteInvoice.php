<?php

namespace App\Http\Livewire\Invoices;

use App\Http\Traits\Livewire\Confirm;
use App\Models\Invoice;
use Livewire\Component;

class DeleteInvoice extends Component
{
    use Confirm;
    public $invoice_id;
    protected $listeners = ['deleteInvoice', 'validateAuthorization'];


    public function render()
    {
        return view('livewire.invoices.delete-invoice');
    }
    public function deleteInvoice($data)
    {
        $id = $data['data']['value'];
        $invoice = Invoice::whereId($id)->with('payments', 'details.product.units', 'taxes', 'comprobante')->first();
        $this->restoreComprobante($invoice->comprobante);
        $this->deleteDetails($invoice);
        $this->deleteTaxes($invoice);
        $this->deletePayments($invoice);
        $invoice->delete();
        $this->emit('showAlert', 'Factura anulada existosamente', 'success');
        $this->emitUp('refreshLivewireDatatable');
    }
    public function restoreComprobante($comprobante)
    {
        if ($comprobante) {
            $comprobante->update([
                'status' => 'disponible'
            ]);
        }
    }
    public function deleteTaxes($invoice)
    {
        $invoice->taxes()->sync([]);
    }
    public function deleteDetails($invoice)
    {
        foreach ($invoice->details as $det) {
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
        foreach ($invoice->payments as $pay) {
            $pay->delete();
        }
    }
}
