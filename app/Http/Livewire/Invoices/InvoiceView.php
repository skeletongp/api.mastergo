<?php

namespace App\Http\Livewire\Invoices;

use App\Events\NewInvoice;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class InvoiceView extends Component
{
    public $pdfLetter, $pdfThermal, $thermal = true;
    public  $currentInvoice, $invoice, $payment;
    use WithPagination;


    protected $queryString = [
        'pdfLetter', 'pdfThermal', 'thermal'

    ];
    protected $listeners=['setPDF'];
    
    public function render()
    {
        $invoices = auth()->user()->place->invoices()->orderBy('updated_at', 'desc')
        ->where('status', '!=', 'waiting')->with('seller','contable','client','details.product.units','details.taxes','details.unit', 'payment','store.image','payments.pdf', 'comprobante','pdf','place.preference')->paginate(6);
        
        if ($invoices->count() && !$this->pdfThermal) {
            $this->pdfThermal = $invoices->first()->pdfThermal;
            $this->pdfLetter = $invoices->first()->pdfLetter;
        }
        if (!$this->currentInvoice && $invoices->count()) {
            $this->currentInvoice = $invoices->first();
            $this->invoice = $invoices->first();
            $this->payment = $this->invoice->payment;
        }
        return view('livewire.invoices.invoice-view', ['invoices' => $invoices]);
    }
    public function setPDF($id)
    {
        $invoice = Invoice::whereId($id)->with('seller','contable','client','details.product.units','details.taxes','details.unit', 'payment','store.image','payments.pdf', 'comprobante','pdf','place.preference','creditnote')->first();
        $this->emit('changeInvoice', $invoice, false);
        $this->pdfLetter = $invoice->pdfLetter;
        $this->pdfThermal = $invoice->pdfThermal;
        $this->currentInvoice = $invoice;
        $this->invoice = $invoice;
        $this->payment = $this->invoice->payment;
        $this->render();
    }
    public function toggleThermal()
    {
        $this->thermal = !$this->thermal;
        $this->render();
    }
}
