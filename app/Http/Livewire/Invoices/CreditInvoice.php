<?php

namespace App\Http\Livewire\Invoices;

use App\Models\Credit;
use App\Models\Invoice;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class CreditInvoice extends LivewireDatatable
{
    public function builder()
    {
        $place=auth()->user()->place;
        $credits=Credit::where("creditable_type", Invoice::class);
    }

    public function columns()
    {
        //
    }
}
