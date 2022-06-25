<?php

namespace App\Http\Livewire\Invoices;

use App\Models\Invoice;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class InvoiceList extends LivewireDatatable
{

    public$hideResults=true;
    public $headTitle="Facturas";
    public $padding="px-2";
    public $perPage=15;
    public function builder()
    {
        $invoices = auth()->user()->place->invoices()->orderBy('updated_at', 'desc')
            ->where('status', 'cerrada')->with('pdf', 'payment','payments','client');
        return $invoices;
    }

    public function columns()
    {
        $invoices = $this->builder()->get()->toArray();
        return [
            Column::name('number')->label('Orden')->searchable()->sortable(),
            Column::callback(['id', 'client_id'], function ($id) use ($invoices) {
                $result = arrayFind($invoices, 'id', $id);
                return $result['client']['fullname'];

            })->label('Cliente'),
            Column::name('uid')->callback(['uid','id'], function ($total, $id) use ($invoices) {
                $result = arrayFind($invoices, 'id', $id);
                $debe=" <span class='fas fa-times text-red-400'></span>";
                $pagado=" <span class='fas fa-check text-green-400'></span>";
                $mark=$pagado;
                if ($result['rest']>0) {
                  $mark=$debe;
                } 
                
                return '$' . formatNumber($result['payment']['total']).$mark;
            })->label('Monto'),
            Column::callback('id', function($id) use ($invoices){
                return view('livewire.invoices.includes.actions',['value'=>$id]);
            })->label('Acciones')->contentAlignCenter()->headerAlignCenter()
        ];
    }
    public function cellClasses($row, $column)
    {
        return
            'whitespace-nowrap overflow-hidden overflow-ellipsis text-gray-900 px-3 py-2';
    }
   
}
