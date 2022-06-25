<?php

namespace App\Http\Livewire\Dashboard;

use Carbon\Carbon;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class LastSales extends LivewireDatatable
{
    public $perPage=5;
    public $headTitle="Últimas ventas";
    public function builder()
    {
        $invoices=auth()->user()->place->invoices()->where('created_at', '>=', Carbon::now()->subWeek())
        ->orderBy('created_at','desc')->where('status','!=','anulada')->with('payment','client','seller','contable','payments');
        return $invoices;
    }

    public function columns()
    {
        $invoices = $this->builder()->get()->toArray();
        return [
            Column::name('id')->callback(['id'], function($id) use ($invoices){
                $result = arrayFind($invoices, 'id', $id);
                if ($result['rest']>0) {
                    return "  <a href=".route('invoices.show', [$id,'includeName'=>'showpayments','includeTitle'=>'Pagos']).
                    "><span class='fas w-8 text-center fa-dollar-sign'></span> </a>";
                } else {
                    return "  <a href=".route('invoices.show', $id)."><span class='fas w-8 text-center fa-eye'></span> </a>";
                }
                
           
            })->label(''),
            DateColumn::name('created_at')->label('Hora')->format('h:i A'),
            Column::name('client.name')->callback(['client_id', 'id' ], function ($client, $id) use ($invoices) {
                $result = arrayFind($invoices, 'id', $id);
                return $result['client']['fullname'];
            })->label('Cliente'),
            Column::callback(['uid', 'id'], function ($total, $id) use ($invoices) {
                $result = arrayFind($invoices, 'id', $id);
                return '$' . formatNumber($result['payment']['total']);
            })->label("Monto"),
            Column::name('client.id')->callback(['id', 'client_id'], function ($id, $client_id) use ($invoices) {
                $result = arrayFind($invoices, 'id', $id);
                return '$'.formatNumber( array_sum(array_column($result['payments'],'payed')));
            })->label('Pagado'),
            Column::name('rest')->callback(['rest'], function ($rest) {
                return '$'. formatNumber($rest);
            })->label('Resta'),
        ];
    }
}