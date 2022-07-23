<?php

namespace App\Http\Livewire\Products\Includes;

use App\Http\Livewire\UniqueDateTrait;
use Carbon\Carbon;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class ProductDetails extends LivewireDatatable
{

    use UniqueDateTrait;
    public $product;
    public $headTitle='Historial de ventas ';
    public $padding='px-2';
    public function builder()
    {
        
         return $this->product->details()->with('unit','detailable.client');
    }

    public function columns()
    {
        $details=$this->builder()->get()->toArray();
        return [
            DateColumn::name('created_at')->label('Fecha')->format('d/m/Y h:i A')->filterable(),
            Column::callback('cant', function($cant){
                return formatNumber($cant);
            })->label('Cant.')->enableSummary(),
            Column::callback(['discount_rate'], function($discount_rate){
                return formatNumber($discount_rate*100).'%';
            })->label('Desc.'),
            Column::callback(['detailable_id','id'], function($det, $id) use ($details){
                $result=arrayFind($details, 'id',$id);
                return $result['detailable']['number'];
            })->label('Fact.'),
            Column::callback(['total'], function($total){
                return '$'.formatNumber($total);
            })->label('Monto')->enableSummary()->contentAlignRight(),
          
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