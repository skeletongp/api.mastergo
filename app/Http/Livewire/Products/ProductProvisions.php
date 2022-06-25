<?php

namespace App\Http\Livewire\Products;

use Illuminate\Support\Facades\DB;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ProductProvisions extends LivewireDatatable
{
    public $product;
    public $headTitle="Provisiones";
    public $padding="px-2 ";
    public function builder()
    {
        $provisions=$this->product->provisions()->groupBy('code')->orderBy('id','desc')
        ->select(DB::raw('id, sum(cant) AS cant, cost, created_at, provider_id, atribuible_type, atribuible_id'))
        ->with('provider','atribuible');
        return $provisions;
    }

    public function columns()
    {
        $provisions=$this->builder()->get()->toArray();
            return [
                Column::index($this,'code'),
                Column::callback('code', function($code){
                    return $code;
                })->label('Cod.'),
                Column::callback(['cant','id'], function($cant, $id) use ($provisions){
                    $result=arrayFind($provisions, 'id',$id);
                    return formatNumber($result['cant']);
                })->label('Cant.'),
                Column::callback(['cost'], function($cost){
                    return '$'.formatNumber($cost);
                })->label('Costo.'),
                Column::callback(['atribuible_id','id'], function($atr, $id) use ($provisions){
                    $result=arrayFind($provisions, 'id',$id);
                    return $result['atribuible']['symbol'];
                })->label('UND'),
                Column::callback(['created_at','id'], function($created, $id) use ($provisions){
                    $result=arrayFind($provisions, 'id',$id);
                    return '$'.formatNumber(removeComma($result['cant'])*removeComma($result['cost']));
                })->label('Total')->enableSummary()->contentAlignRight()->headerAlignRight(),
                Column::callback(['provider_id','id'], function($provider, $id) use ($provisions){
                    $result=arrayFind($provisions, 'id',$id);
                    return $result['provider']['fullname'];
                })->label('Proveedor'),
              
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
           
            return "<div class='font-bold text-right'>". '$'.formatNumber(array_sum(array_column($results, $column)))."</div>";;
        } catch (\TypeError $e) {
            return '';
        }
    }
    public function cellClasses($row, $column)
    {
        return
            'whitespace-nowrap overflow-hidden  text-gray-900 px-2 py-2';
    }
}