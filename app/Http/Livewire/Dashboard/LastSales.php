<?php

namespace App\Http\Livewire\Dashboard;

use Carbon\Carbon;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class LastSales extends LivewireDatatable
{
    public $perPage=5;
    public function builder()
    {
        $invoices=auth()->user()->place->invoices()->where('created_at', '>=', Carbon::now()->subWeek())
        ->where('status', '!=', 'waiting')
        ->orderBy('created_at','desc')->with('payment','client','seller','contable');
        return $invoices;
    }

    public function columns()
    {
        $invoices = $this->builder()->get()->toArray();
        return [
            Column::index($this),
            DateColumn::name('created_at')->label('fecha')->format('d/m/Y H:i A'),
            Column::name('client.name')->callback(['uid', 'client_id'], function ($uid) use ($invoices) {
                $result = arrayFind($invoices, 'uid', $uid);
                return $result['client']['fullname'];
            })->label('Cliente'),

            Column::name('seller.name')->callback(['uid', 'day'], function ($uid) use ($invoices) {
                $result = arrayFind($invoices, 'uid', $uid);
                return $result['seller']['fullname'];
            })->label('Vendedor'),
            Column::name('contable.name')->callback(['uid', 'id'], function ($uid) use ($invoices) {
                $result = arrayFind($invoices, 'uid', $uid);
                return $result['contable']['fullname'];
            })->label('Cajero'),
            Column::name('condition')->label("Condición"),
            Column::callback('payment.amount:sum', function ($amount) {
                return '$' . formatNumber($amount);
            })->label("Monto"),
        ];
    }
}