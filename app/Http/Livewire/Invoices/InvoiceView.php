<?php

namespace App\Http\Livewire\Invoices;

use App\Events\NewInvoice;
use App\Mail\CotizeMail;
use App\Mail\InvoiceEmail;
use App\Models\Invoice;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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
        
        return view('livewire.invoices.invoice-view');
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
    public function updatePDF()
    {
        if($this->currentInvoice){
            
            $this->emit('showAlert','PDF actualizado correctamente','success');
            setPDFPath($this->currentInvoice);
            $this->setPDF($this->currentInvoice->id);
        }
    }
    public function sendInvoice(){
        if($this->currentInvoice){
            $invoice=$this->currentInvoice;
            Mail::to([$invoice->client->email, $invoice->store->email])->send(new InvoiceEmail($invoice));
            sendInvoiceWS($invoice->pdf->pathLetter, $invoice->client->contact->cellphone, $invoice->id);
            $this->emit('showAlert','Factura enviada correctamente','success');
        }
    }
}
