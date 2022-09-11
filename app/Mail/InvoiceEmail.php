<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $invoice;
    public function __construct($invoice)
    {
        $this->invoice=$invoice;
    }

   
    public function build()
    {
        return $this->view('pages.mails.invoice', ['invoice'=>$this->invoice])
        ->subject('Factura #'.$this->invoice->number.' - '.$this->invoice->client->name)
        ->attach($this->invoice->pdf->pathLetter);
    }
}
