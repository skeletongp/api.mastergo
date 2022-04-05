<?php

namespace App\Http\Livewire\Invoices;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class InvoiceView extends Component
{
    public $pdfPath, $perPage=7;
    use WithPagination;
    protected $queryString = [
        'pdfPath', 'perPage', 
        
    ];
    public function render()
    {
        $invoices = DB::table('atrionstore.invoices')->paginate($this->perPage?:7);
        foreach ($invoices as $ind => $invoice) {
            $invoice->pdf = asset("storage/invoices/fct000000{$ind}.pdf");
        }
        if ($invoices->count() && !$this->pdfPath) {
            $this->pdfPath = $invoices->first()->pdf;
        }
        return view('livewire.invoices.invoice-view', ['invoices' => $invoices]);
    }
    public function setPDF($path)
    {
            $this->pdfPath=$path;
            $this->render();
    }
}
