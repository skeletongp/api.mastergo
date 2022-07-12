<?php

namespace App\Http\Livewire\Invoices\ShowIncludes;

use Carbon\Carbon;

trait ShowCredit
{
    public $modified_ncf, $modified_at, $comment, $creditComprobantes;

    public function createCreditnote(){
        $this->validate([
            'modified_ncf' => 'required',
            'modified_at' => 'required',
            'comment' => 'required',
        ]);
        $this->invoice->creditnote()->create([
            'modified_ncf' => $this->modified_ncf,
            'modified_at' => $this->modified_at,
            'comment' => $this->comment,
            'place_id' => auth()->user()->place->id,
            'user_id' => auth()->user()->id,
        ]);
        $this->emit('showAlert','Nota de crédito creada con éxito','success');
        $this->render();

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

}
