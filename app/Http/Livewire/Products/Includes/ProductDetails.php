<?php

namespace App\Http\Livewire\Products\Includes;

use App\Http\Livewire\UniqueDateTrait;
use Carbon\Carbon;
use App\Http\Classes\Column;
use App\Http\Classes\NumberColumn as ClassesNumberColumn;
use App\Models\Detail;
use Illuminate\Support\Facades\DB;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class ProductDetails extends LivewireDatatable
{

    public $product;
    public $headTitle='Historial de ventas ';
    public $padding='px-2';
    public function builder()
    {
        $details=Detail::where('product_id',$this->product->id)
        ->join('units','details.unit_id','units.id')
        ->join('invoices','details.detailable_id','invoices.id')
        ->where('detailable_type','App\Models\Invoice');
        return $details;
    }

    public function columns()
    {
        return [
            DateColumn::name('details.created_at')->label('Fecha')->format('d/m/Y h:i A')->filterable(),
            ClassesNumberColumn::name('details.cant')->label('Cant')->formatear('number')->enableSummary(),
            ClassesNumberColumn::name('details.price')->label('Precio')->formatear('money'),
            ClassesNumberColumn::raw("CONCAT(ROUND(details.discount_rate*100,2),'%') AS rate")->label('Desc.'),
            ClassesNumberColumn::name('details.total')->label('Total')->formatear('money')->enableSummary(),
            Column::callback(['invoices.id','invoices.number'], function ($id,$number) {
                return "<a class='text-blue-500 hover:underline hover:font-bold' href=".route('invoices.show',$id)."> Fact. ".ltrim(substr($number, strpos($number, '-') + 1), '0')."</a>";
            })->label('Factura'),
        ];
    }
    public function summarize($column)
    {
        $results=json_decode(json_encode($this->results->items()), true);
        foreach ($results as $key => $value) {
            $val=json_decode(json_encode($value), true);
            $results[$key][$column]=preg_replace("/[^0-9 .]/", '', $val[$column]);
        }
        try {

            return "<h1 class='font-bold text-right'>". formatNumber(array_sum(array_column($results, $column)))."</h1>";;
        } catch (\TypeError $e) {
            return '';
        }
    }
    public function cellClasses($row, $column)
    {
        return
            'overflow-hidden overflow-ellipsis whitespace-nowrap  text-gray-900 px-2 py-2';
    }
}
