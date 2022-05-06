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
    public  $currentInvoice;
    use WithPagination;


    protected $queryString = [
        'pdfLetter', 'pdfThermal', 'thermal'

    ];
    protected $listeners=['setPDF'];
    public function render()
    {
        $invoices = auth()->user()->place->invoices()->orderBy('updated_at', 'desc')
            ->where('status', '!=', 'waiting')->with('pdfs','payment')->paginate(6);

        if ($invoices->count() && !$this->pdfThermal) {
            $this->pdfThermal = $invoices->first()->pdfThermal;
            $this->pdfLetter = $invoices->first()->pdfLetter;
        }
        if (!$this->currentInvoice) {
            $this->currentInvoice = $invoices->first();
        }
        return view('livewire.invoices.invoice-view', ['invoices' => $invoices]);
    }
    public function setPDF($id)
    {
        $invoice = Invoice::find($id);
        $this->pdfLetter = $invoice->pdfLetter;
        $this->pdfThermal = $invoice->pdfThermal;
        $this->currentInvoice = $invoice;
        $this->render();
    }
    public function toggleThermal()
    {
        $this->thermal = !$this->thermal;
        $this->render();
    }
}
