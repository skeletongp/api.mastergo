<?php

namespace App\Http\Livewire\Invoices;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class InvoiceView extends Component
{
    public $pdfLetter, $pdfThermal, $perPage=7, $thermal=true;
    use WithPagination;
    protected $queryString = [
        'pdfLetter', 'pdfThermal', 'perPage', 
        
    ];
    public function render()
    {
        $invoices = auth()->user()->place->invoices()->orderBy('id','desc')->paginate();
        
        if ($invoices->count() && !$this->pdfThermal) {
            $this->pdfThermal = $invoices->first()->pdfThermal;
            $this->pdfLetter = $invoices->first()->pdfLetter;
        }
        return view('livewire.invoices.invoice-view', ['invoices' => $invoices]);
    }
    public function setPDF($thermal,$letter)
    {
            $this->pdfLetter=$letter;
            $this->pdfThermal=$thermal;
            $this->render();
    }
    public function toggleThermal()
    {
        $this->thermal=!$this->thermal;
        $this->render();
    }
}
