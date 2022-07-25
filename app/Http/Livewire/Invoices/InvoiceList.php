<?php

namespace App\Http\Livewire\Invoices;

use App\Models\Invoice;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class InvoiceList extends LivewireDatatable
{

    public $hideResults = true;
    public $hideable = "select";
    public $headTitle = "Facturas";
    public $padding = "px-2";
    public $perPage = 15;
    public function builder()
    {
        $invoices = auth()->user()->place->invoices()->orderBy('invoices.updated_at', 'desc')
            ->join('clients', 'clients.id', '=', 'invoices.client_id')
            ->select('invoices.*', 'clients.name as client_name')
            ->where('status', 'cerrada')->with('pdf', 'payment', 'payments', 'client')
           ;
        return $invoices;
    }

    public function columns()
    {
        $invoices = $this->builder()->get()->toArray();
        return [
            Column::name('number')->label('Orden')->searchable()->sortable(),
            DateColumn::name('created_at')->label('hora')->format('d/m/Y H:i')->hide(),
            Column::callback(['clients.name','name'], function($cltname, $name){
                return ellipsis($name?:$cltname, 16);
            })->label('Cliente')->searchable(),
            Column::callback(['uid', 'id'], function ($total, $id) use ($invoices) {
                $result = arrayFind($invoices, 'id', $id);
                $debe = " <span class='fas fa-times text-red-400'></span>";
                $pagado = " <span class='fas fa-check text-green-400'></span>";
                $mark = $pagado;
                if ($result['rest'] > 0) {
                    $mark = $debe;
                }
                return '$' . formatNumber($result['payment']['total']) . $mark;
            })->label('Monto'),
            Column::callback('id', function ($id) use ($invoices) {
                return view('livewire.invoices.includes.actions', ['value' => $id]);
            })->label('Acciones')->contentAlignCenter()->headerAlignCenter()
        ];
    }
    public function cellClasses($row, $column)
    {
        return
            'whitespace-nowrap overflow-hidden overflow-ellipsis text-gray-900 px-3 py-2';
    }
}
