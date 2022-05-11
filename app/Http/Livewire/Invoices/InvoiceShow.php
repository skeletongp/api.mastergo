<?php

namespace App\Http\Livewire\Invoices;

use App\Http\Livewire\Invoices\ShowIncludes\Showclient;
use App\Models\Invoice;
use Livewire\Component;

class InvoiceShow extends Component
{
    use Showclient;

    public Invoice $invoice;
    public $includeName="showclient";

    protected $rules=[
        'client'=>'required'
    ];

    public function mount()
    {
        $store = auth()->user()->store;
        $this->clients=$store->clients()->orderBy('name')->get();
        $this->client=$this->invoice->client->toArray();
        $this->client_code=$this->client['code'];
    }
    public function render()
    {
        return view('livewire.invoices.invoice-show');
    }
}
