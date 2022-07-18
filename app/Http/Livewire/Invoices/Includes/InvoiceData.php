<?php

namespace App\Http\Livewire\Invoices\Includes;

use App\Models\Invoice;
use Carbon\Carbon;

trait InvoiceData
{

    public $number, $ncf, $condition = "DE CONTADO", $type, $vence, $seller, $compAvail = true, $comprobante_id;

    public function checkComprobante($type): bool
    {
        $comprobante = auth()->user()->store->comprobantes()
            ->where('type', array_search($type, Invoice::TYPES))->where('status', 'disponible')
            ->orderBy('number')->first();
        if ($comprobante) {
            $this->comprobante_id = $comprobante->id;
            $this->type = $type;
            $this->ncf=$comprobante->ncf;
            return true;
        } else {
            $this->type = 'B00';
            $this->ncf='B0000000000';
            return false;
        };
        
    }
    public function updatedType()
    {
        $value = $this->type;

        if ($value != 'B00') {
            $this->compAvail = $this->checkComprobante($value);
            if (!$this->compAvail) {
                $this->form['type'] = 'B00';
            }
        }
    }
    public function updatedCondition()
    {
        $cond = $this->condition;
        switch ($cond) {
            case '1 A 15 DÍAS':
                $this->vence = Carbon::now()->addDays(15)->format('Y-m-d');
                break;
            case '16 A 30 DÍAS':
                $this->vence = Carbon::now()->addDays(30)->format('Y-m-d');
                break;
            case '31 A 45 DÍAS':
                $this->vence = Carbon::now()->addDays(45)->format('Y-m-d');
                break;
            default:
                $this->vence = Carbon::now()->addDays(30)->format('Y-m-d');
                break;
        }
    }
}
