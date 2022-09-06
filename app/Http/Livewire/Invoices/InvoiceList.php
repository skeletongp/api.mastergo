<?php

namespace App\Http\Livewire\Invoices;

use App\Http\Classes\NumberColumn;
use App\Models\Invoice;
use App\Models\Payment;
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
        $place=auth()->user()->place;
        $invoices = 
        Payment::where('invoices.place_id', $place->id)
        ->orderBy('invoices.created_at', 'desc')
        ->join('invoices', 'payments.payable_id', '=', 'invoices.id')
        ->where('payments.payable_type', '=', 'App\Models\Invoice')
        ->where('status', '=', 'cerrada')
        ->groupBy('invoices.id');
           ;
        return $invoices;
    }

    public function columns()
    {
        return [
            
            Column::callback(['invoices.number','invoices.rest'], function ($number, $rest){
                $number = ltrim(substr($number, strpos($number, '-') + 1), '0');
                $debe = " <span class='fas fa-times text-red-400 pr-2'></span>";
                $pagado = " <span class='fas fa-check text-green-400 pr-2'></span>";
                $mark = $pagado;
                if ($rest > 0) {
                    $mark = $debe;
                }
                return $mark.$number;
            })->label('Nro.')->searchable(),
            DateColumn::name('invoices.created_at')->label('hora')->format('d/m/Y H:i')->hide(),
            Column::callback(['invoices.name'], function( $name){
                return ellipsis($name, 16);
            })->label('Cliente')->searchable(),
            NumberColumn::raw('total')->label('Total')->searchable()->formatear('money'),
            NumberColumn::raw('invoices.rest')->label('Resta')->searchable()->formatear('money'),
            Column::callback('invoices.id', function ($id)  {
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
