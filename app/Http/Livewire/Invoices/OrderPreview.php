<?php

namespace App\Http\Livewire\Invoices;

use App\Models\Invoice;
use App\Http\Classes\Column;use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class OrderPreview extends LivewireDatatable
{
    public $invoice;
    public $padding='px-2';
    public $headTitle="Detalles del pedido";
    public $hideable="select";
    protected $queryString = [
        'page' => ['except' => 1, 'as' => 'page_preview'],
        ];
    public function builder()
    {
        $invoice=Invoice::find($this->invoice['id']);
        $details=$invoice->details()
        ->join('products', 'products.id',  'details.product_id')
        ->join('units', 'units.id',  'details.unit_id')

        ;
        return $details;
    }

    public function columns()
    {
        return [
            Column::name('products.name')->label("Producto")->searchable(),
            Column::callback('cant', function($cant){
                return formatNumber($cant);
            })->label("Cant."),
            Column::name('units.symbol')->label("Und.")->searchable(),

            Column::callback('price', function($price){
                return '$'.formatNumber($price);
            })->label("Precio"),
            Column::callback('discount', function($desc){
                return '$'.formatNumber($desc);
            })->label("Desc."),
            Column::callback('subtotal', function($subtotal){
                return '$'.formatNumber($subtotal);
            })->label("Subt."),
            Column::callback('taxTotal', function($taxTotal){
                return '$'.formatNumber($taxTotal);
            })->label("ITBIS")->hide(),
            Column::callback('total', function($total){
                return '$'.formatNumber($total);
            })->label("Total")->enableSummary(),
        ];
    }
    public function summarize($column)
    {

        $results = json_decode(json_encode($this->results->items()), true);
        foreach ($results as $key => $value) {
            $val = json_decode(json_encode($value), true);
            $results[$key][$column] = preg_replace("/[^0-9 .]/", '', $val[$column]);
        }
        try {
            return " 
            <div class='flex justify-between items-center'>
                <span class='font-bold text-right'>" . '$' . formatNumber(array_sum(array_column($results, $column))) . "</span>
            </div>";
        } catch (\TypeError $e) {
            return '';
        }
    }
}