<?php

namespace App\Http\Livewire\Invoices;

use App\Http\Helper\Universal;
use App\Models\Invoice;
use App\Models\User;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderView extends LivewireDatatable
{
    use AuthorizesRequests;

   
    public function builder()
    {
        $invoices = auth()->user()->place->invoices()->with('seller','details','details.taxes')
            ->orderBy('invoices.id', 'desc')->where('status', 'waiting');
        return $invoices;
    }

    public function columns()
    {
        $invoices = $this->builder()->get()->toArray();
        return [
            Column::name('number')->label("Nº. Pedido"),
            Column::callback('amount', function ($amount) {
                return '$' . formatNumber($amount);
            })->label("Subtotal"),
            Column::name('seller.name')->callback(['uid', 'day'], function ($uid) use ($invoices) {
                $result = $this->arrayFind($invoices, 'uid', $uid);
                return $result['seller']['fullname'];

            })->label('Vendedor'),
            Column::callback('uid', function ($uid) use ($invoices) {
                $result = $this->arrayFind($invoices, 'uid', $uid);
                return view('pages.invoices.order-page', ['invoice' => $result]);
            })->label('Acción')
        ];
    }
    public function arrayFind(array $array, $key, $value)
    {
        $result = 0;
        foreach ($array as $ind => $item) {
            if ($array[$ind][$key] == $value) {
                $result = $item;
            }
        }
        return $result;
    }
}
