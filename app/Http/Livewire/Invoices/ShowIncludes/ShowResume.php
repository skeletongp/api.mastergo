<?php

namespace App\Http\Livewire\Invoices\ShowIncludes;

use Carbon\Carbon;

trait ShowResume
{
   public function sendByWS(){
    $path=$this->invoice->pdf->pathLetter;
    $phone=optional($this->invoice->client->contact)->cellphone;
    $number=$this->invoice->number;
    sendInvoiceWS($path,$phone,$number);
    $this->emit('showAlert','Se ha enviado la factura al cliente','success');
   }
   public function printInvoice(){
    $invoice=$this->invoice->load('seller', 'contable', 'client', 'details.product.units', 'details.taxes', 'details.unit', 'payment', 'store.image', 'payments.pdf', 'comprobante', 'pdf', 'place.preference');
    $this->emit('changeInvoice', $invoice, true);
   }


}
