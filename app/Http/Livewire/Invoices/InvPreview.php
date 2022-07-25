<?php

namespace App\Http\Livewire\Invoices;

use App\Models\Invoice;
use Livewire\Component;

class InvPreview extends Component
{
    public $invoice;
    public function render()
    {
        return view('livewire.invoices.inv-preview');
    }

    public function printPreview(){
        $invoice = Invoice::whereId($this->invoice['id'])->with('seller','contable','client','details.product.units','details.taxes','details.unit', 'payment','store.image','payments.pdf', 'comprobante','pdf','place.preference','creditnote')->first();
        $invoice->note="Para vista previa. No cerrada";
        $this->emit('changeInvoice', $invoice, true);
    }
}
