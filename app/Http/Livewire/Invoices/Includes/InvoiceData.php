<?php

namespace App\Http\Livewire\Invoices\Includes;

use App\Models\Invoice;

trait InvoiceData{

    public function checkComprobante($type): bool
    {
        $comprobante = auth()->user()->store->comprobantes()
            ->where('type', array_search($type, Invoice::TYPES))->where('status', 'disponible')
            ->orderBy('number')->first();
        if ($comprobante) {
            $this->form['comprobante_id'] = $comprobante->id;
            $this->type = $type;
            return true;
        } else {
            $this->type = 'B00';
            return false;
        };
    }
    public function updatedType()
    {   
        $value=$this->type;
        
            if ($value != 'B00') {
                $this->compAvail = $this->checkComprobante($value);
                if (!$this->compAvail) {
                    $this->form['type'] = 'B00';
                }
            }
           
    }
}