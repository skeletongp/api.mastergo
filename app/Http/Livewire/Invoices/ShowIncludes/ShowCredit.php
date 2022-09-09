<?php

namespace App\Http\Livewire\Invoices\ShowIncludes;

use Carbon\Carbon;

trait ShowCredit
{
    public $modified_ncf, $modified_at, $comment, $creditComprobantes, $comprobanteCredit, $amount, $tax;

    public function createCreditnote(){
        if($this->invoice->creditnote){
            $this->emit('showAlert','La factura ya tiene una nota de crédito','error');
            return;
        }
        $this->validate([
            'modified_ncf' => 'required',
            'modified_at' => 'required',
            'comment' => 'required',
        ]);
        $this->closeComprobante();

        $this->invoice->creditnote()->create([
            'modified_ncf' => $this->modified_ncf,
            'modified_at' => $this->modified_at,
            'comment' => $this->comment,
            'place_id' => auth()->user()->place->id,
            'user_id' => auth()->user()->id,
            'comprobante_id'=>$this->comprobanteCredit->id,
        ]);
        $this->emit('showAlert','Nota de crédito creada con éxito','success');
        $this->render();

    }
    public function closeComprobante(){
        $store = auth()->user()->store;
        $this->comprobanteCredit=$store->comprobantes()->where('ncf',$this->modified_ncf)
        ->where('status','disponible')->orderBy('number')
        ->first();
        $this->comprobanteCredit->update([
            'status'=>'usado',
            'period'=>Carbon::now()->format('Ym'),
            'user_id'=>auth()->user()->id,
            'client_id'=>$this->invoice->client_id,
            'place_id'=>auth()->user()->place->id,
            
        ]);
    }

    public function initCreditnote(){
        $store = auth()->user()->store;
        $comprobante=$store->comprobantes()->where('prefix','B04')
        ->where('status','disponible')->orderBy('number')
        ->first();
        $this->modified_ncf=optional($comprobante)->ncf;
        $this->modified_at=date('Y-m-d');
        if($this->invoice->creditnote){
            $this->modified_ncf=$this->invoice->creditnote->modified_ncf;
            $this->modified_at=Carbon::parse($this->invoice->creditnote->modified_at)->format('Y-m-d');
            $this->comment=$this->invoice->creditnote->comment;
        }
    }
    public function printCreditNote(){
        $invoice=$this->invoice;
        $invoice =$invoice->load('seller', 'contable', 'client', 'details.product.units', 'details.taxes', 'details.unit', 'payment', 'store.image', 'payments.pdf', 'comprobante', 'pdf', 'place.preference');
        $this->emit('changeInvoice', $invoice, true, true);
    }

}
