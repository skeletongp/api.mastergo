<?php

namespace App\Http\Livewire\Invoices;

use App\Models\Invoice;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class InvoiceList extends LivewireDatatable
{

    public$hideResults=true;
    public $headTitle="Facturas";
    public $perPage=15;
    public function builder()
    {
        $invoices = auth()->user()->place->invoices()->orderBy('updated_at', 'desc')
            ->where('status', '!=', 'waiting')->with('pdf', 'payment','payments','client');
        return $invoices;
    }

    public function columns()
    {
        $invoices = $this->builder()->get()->toArray();
        return [
            Column::name('number')->label('Orden')->searchable()->sortable(),
            Column::name('client.name')->callback(['id', 'client_id'], function ($id) use ($invoices) {
                $result = arrayFind($invoices, 'id', $id);
                return $result['client']['fullname'];

            })->label('Cliente'),
            Column::name('uid')->callback(['uid','id'], function ($total, $id) use ($invoices) {
                $result = arrayFind($invoices, 'id', $id);
                return '$' . formatNumber($result['payment']['total']);
            })->label('Monto'),
            Column::name('id')->label('Ver')->view('livewire.invoices.includes.setPDF')->sortable(),
        ];
    }
    public function cellClasses($row, $column)
    {
        return
            'whitespace-nowrap overflow-hidden overflow-ellipsis text-gray-900 px-3 py-2';
    }
   
}
