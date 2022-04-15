<?php

namespace App\Http\Livewire\Invoices;

use App\Http\Helper\Universal;
use App\Models\Invoice;
use Livewire\Component;

class OrderConfirm extends Component
{
    public  $invoice;
    protected $rules = [
        'invoice' => 'required',
        'invoice.amount' => 'required|numeric',
        'invoice.discount' => 'required|numeric',
        'invoice.total' => 'required|numeric',
        'invoice.tax' => 'required|numeric',
        'invoice.payed' => 'required|numeric',
        'invoice.efectivo' => 'required|numeric',
        'invoice.tarjeta' => 'required|numeric',
        'invoice.transferencia' => 'required|numeric',
        'invoice.pdfLetter' => 'required|numeric',
        'invoice.pdfThermal' => 'required|numeric',
        'invoice.contable_id' => 'required|numeric',
        'invoice.client_id' => 'required|numeric',
        'invoice.type' => 'required|numeric',
        'invoice.note' => 'required|numeric',
    ];

    public function mount($invoice)
    {
        $this->invoice = $invoice;
    }
    public function render()
    {
        $clients = auth()->user()->store->clients->pluck('name', 'id');
        dd($clients);
        return view('livewire.invoices.order-confirm', compact('clients'));
    }
    public function updatedInvoice($value, $key)
    {
        switch ($key) {
            case 'type':
                if ($value !== 'B00' && $value !== 'B14'){
                    $taxTotal=array_sum(array_column($this->invoice['details'], 'taxtotal   '));
                    $this->invoice['tax']=Universal::formatNumber($taxTotal);
                } else{
                    $this->invoice['tax']=Universal::formatNumber(0);
                }
                    break;

            default:
                # code...
                break;
        }
    }
}
