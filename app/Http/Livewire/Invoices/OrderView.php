<?php

namespace App\Http\Livewire\Invoices;

use App\Http\Helper\Universal;
use App\Models\Invoice;
use App\Models\User;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Mediconesystems\LivewireDatatables\TimeColumn;

class OrderView extends LivewireDatatable
{
    use AuthorizesRequests;
    public $hideable="select";
    public $perPage=5;
   
    public function builder()
    {
        $invoices = auth()->user()->place->invoices()->with('seller','client','details','details.taxes', 'payment')
            ->orderBy('invoices.id', 'desc')->where('status', 'waiting');
        return $invoices;
    }

    public function columns()
    {
        $invoices = $this->builder()->get()->toArray();
        return [
            Column::name('number')->label("Nro."),
            TimeColumn::name('created_at')->label("Hora")->hide(),
            Column::callback('payment.amount:sum', function ($amount) {
                return '$' . formatNumber($amount);
            })->label("Subtotal"),
            Column::name('client.name')->callback(['uid', 'client_id'], function ($uid) use ($invoices) {
                $result = arrayFind($invoices, 'uid', $uid);
                return $result['client']['fullname'];

            })->label('Cliente'),

            Column::name('seller.name')->callback(['uid', 'day'], function ($uid) use ($invoices) {
                $result = arrayFind($invoices, 'uid', $uid);
                return $result['seller']['fullname'];

            })->label('Vendedor'),

            Column::name('condition')->label("Condición"),
            Column::name('type')->callback('type', function($type){
                return array_search($type, Invoice::TYPES);
            })->label("Tipo"),

            Column::callback('uid', function ($uid) use ($invoices) {
                $result = arrayFind($invoices, 'uid', $uid);
                return view('pages.invoices.order-page', ['invoice' => $result]);
            })->label('Acción')
        ];
    }
    
     
}
