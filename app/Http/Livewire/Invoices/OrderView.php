<?php

namespace App\Http\Livewire\Invoices;

use App\Http\Helper\Universal;
use App\Models\Invoice;
use App\Models\User;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Mediconesystems\LivewireDatatables\Action;
use Mediconesystems\LivewireDatatables\TimeColumn;

class OrderView extends LivewireDatatable
{
    use AuthorizesRequests;
    public $hideable="select";
    public $headTitle="Pedidos Pendientes";
    public $perPage=5;

    public function builder()
    {
        $invoices = auth()->user()->place->invoices()->with('seller','contable','client','details.product.units','details.taxes','details.unit', 'payment','store.image','payments.pdf', 'comprobante','pdf','place.preference')
            ->orderBy('invoices.id', 'desc')->where('status', 'waiting');
        
        return $invoices;
    }

    public function columns()
    {
        $invoices = $this->builder()->get()->toArray();
        $store = auth()->user()->store;
        $banks = $store->banks()->pluck('bank_name', 'id');
        return [
            Column::name('number')->label("Nro."),
            TimeColumn::name('created_at')->label("Hora")->hide(),
            Column::name('client.name')->callback(['client_id', 'id'], function ($clt, $uid) use ($invoices) {
                $result = arrayFind($invoices, 'id', $uid);
                return ellipsis($result['name']?:$result['client']['name'], 16);

            })->label('Cliente'),
            Column::callback(['deleted_at','id'], function ($amount, $id) use ($invoices)  {
                $result = arrayFind($invoices, 'id', $id);
                
                return '$' . formatNumber($result['payment']['total']);
            })->label("Monto"),
            Column::name('seller.name')->callback(['uid', 'day'], function ($uid) use ($invoices) {
                $result = arrayFind($invoices, 'uid', $uid);
                return ellipsis($result['seller']['fullname'], 16);
            })->label('Vendedor'),
           
            Column::name('condition')->label("Condición"),
            Column::callback('id', function ($id) use ($invoices,  $banks) {
                $result = arrayFind($invoices, 'id', $id);
                return view('pages.invoices.order-page', ['invoice' => $result, 'banks'=>$banks]);
            })->label('Acción'),
            Column::callback(['uid','id'], function ($uid) use ($invoices) {
                $result = arrayFind($invoices, 'uid', $uid);
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