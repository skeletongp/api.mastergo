<?php

namespace App\Http\Livewire\Dashboard;

use App\Http\Classes\NumberColumn;
use App\Models\Invoice;
use Carbon\Carbon;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class LastSales extends LivewireDatatable
{
    public $perPage = 10;
    public $headTitle = "Últimas ventas";
    public $padding = "px-2";
    public function builder()
    {
        $this->perPage = 10;
        $place = auth()->user()->place;
        $invoices =
            Invoice::where('invoices.place_id', $place->id)
            ->where('invoices.created_at', '>=', Carbon::now()->subWeek())
            ->orderBy('invoices.created_at', 'desc')->where('invoices.status', '!=', 'anulada')
            ->leftJoin('payments', 'payments.payable_id', '=', 'invoices.id')
            ->where('payments.payable_type', 'App\Models\Invoice')
            ->leftjoin('clients', 'clients.id', '=', 'invoices.client_id')
            ->selectRaw('invoices.*, clients.name as client_name')
            ->groupBy('invoices.id');;
        return $invoices;
    }

    public function columns()
    {
        return [
            Column::callback(['rest', 'id'], function ($rest, $id) {
                if ($rest > 0) {
                    return "  <a href=" . route('invoices.show', [$id, 'includeName' => 'showpayments', 'includeTitle' => 'Pagos']) .
                        "><span class='fas w-8 text-center fa-dollar-sign'></span> </a>";
                } else {
                    return "  <a href=" . route('invoices.show', $id) . "><span class='fas w-8 text-center fa-eye'></span> </a>";
                }
            })->label(''),
            Column::callback('number', function ($number){
                return ltrim(substr($number, strpos($number, '-') + 1), '0');
            })->label('Nº.'),
            DateColumn::name('invoices.created_at')->label('Hora')->format('d/m H:i'),
            Column::callback(['clients.name', 'name'], function ($client, $name)  {
               
                $name=ellipsis($name?:$client,20);
                return $name;
            })->label('Cliente')->searchable(),
            NumberColumn::raw('total')->label('Monto')->searchable()->formatear('money'),
            NumberColumn::raw('SUM(payed-cambio) AS payed')->label('Pagado')->formatear('money'),
            NumberColumn::raw('invoices.rest')->label('Resta')->formatear('money'),
           
        ];
    }
}
