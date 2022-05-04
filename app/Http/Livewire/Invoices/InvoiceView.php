<?php

namespace App\Http\Livewire\Invoices;

use App\Events\NewInvoice;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class InvoiceView extends Component
{
    public $pdfLetter, $pdfThermal, $perPage = 7, $thermal = true;
    public  $currentInvoice;
    use WithPagination;


    protected $queryString = [
        'pdfLetter', 'pdfThermal', 'perPage', 'page'

    ];
    public function render()
    {
        $invoices = auth()->user()->place->invoices()->orderBy('updated_at', 'desc')
            ->where('status', '!=', 'waiting')->with('pdfs')->paginate(6);

        if ($invoices->count() && !$this->pdfThermal) {
            $this->pdfThermal = $invoices->first()->pdfThermal;
            $this->pdfLetter = $invoices->first()->pdfLetter;
        }
        if (!$this->currentInvoice) {
            $this->currentInvoice = $invoices->first();
        }

      
        return view('livewire.invoices.invoice-view', ['invoices' => $invoices]);
    }
    public function setPDF($thermal, $letter, $id)
    {
        $this->pdfLetter = $letter;
        $this->pdfThermal = $thermal;
        $this->currentInvoice = Invoice::find($id);
        $this->render();
    }
    public function toggleThermal()
    {
        $this->thermal = !$this->thermal;
        $this->render();
    }
}
