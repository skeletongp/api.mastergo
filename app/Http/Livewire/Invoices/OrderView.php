<?php

namespace App\Http\Livewire\Invoices;

use App\Http\Helper\Universal;
use App\Models\Invoice;
use App\Models\User;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Mediconesystems\LivewireDatatables\Action;
use Mediconesystems\LivewireDatatables\TimeColumn;

class OrderView extends LivewireDatatable
{
    use AuthorizesRequests;
    public $hideable = "select";
    public $headTitle = "Pedidos Pendientes";
    public $perPage = 7;
    public $padding = 'px-2';

    public function builder()
    {
        $this->perPage = 7;
        $invoices = auth()->user()->place->invoices()
            ->join('clients', 'clients.id', '=', 'invoices.client_id')
            ->select('invoices.*', 'clients.name as client_name')
            ->with('seller', 'contable', 'client', 'details.product.units', 'details.taxes', 'details.unit', 'payment', 'store.image', 'payments.pdf', 'comprobante', 'pdf', 'place.preference')
            ->orderBy('invoices.id', 'desc')->where('status', 'waiting');

        return $invoices;
    }

    public function columns()
    {
        $invoices = $this->builder()->get()->toArray();
        $store = auth()->user()->store;
        $banks = $store->banks()->pluck('bank_name', 'id');
        return [
            Column::name('number')->label("Nro.")->searchable(),
            TimeColumn::name('created_at')->label("Hora"),
            Column::callback(['clients.name', 'name'], function ($cltname, $name) {
                return ellipsis($name ?: $cltname, 16);
            })->label('Cliente')->searchable(),
            Column::callback(['deleted_at', 'id'], function ($amount, $id) use ($invoices) {
                $result = arrayFind($invoices, 'id', $id);
                try {
                    return '$' . formatNumber($result['payment']['total']);
                } catch (\Throwable $th) {
                    Log::error($th->getMessage());
                    Log::info($result);
                    return '$0';
                }
            })->label("Monto"),
            Column::name('seller.name')->callback(['day', 'id'], function ($day, $id) use ($invoices) {
                $result = arrayFind($invoices, 'id', $id);
                return ellipsis($result['seller']['fullname'], 16);
            })->label('Vendedor'),

            Column::name('condition')->label("Condición"),
            Column::callback('id', function ($id) use ($invoices,  $banks) {
                $result = arrayFind($invoices, 'id', $id);
                return view('pages.invoices.order-page', ['invoice' => $result, 'banks' => $banks]);
            })->label('Acción'),
            Column::callback(['uid', 'id'], function ($uid, $id) use ($invoices) {
                $result = arrayFind($invoices, 'id', $id);
                return view('pages.invoices.delete-invoice-page', ['invoice' => $result]);
            })->label('Anular')->alignCenter()

        ];
    }
    public function cellClasses($row, $column)
    {
        return
            'whitespace-nowrap overflow-hidden overflow-ellipsis text-gray-900 px-3 py-2';
    }
}
