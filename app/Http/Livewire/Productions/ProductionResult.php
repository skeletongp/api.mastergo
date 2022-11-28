<?php

namespace App\Http\Livewire\Productions;

use App\Http\Classes\Column;use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class ProductionResult extends LivewireDatatable
{
    public $production;
    public $padding="px-2";
    public $headTitle="";
    public function builder()
    {   
        $this->perPage=5;
        $title="<div class='flex justify-between items-center'>
            <span> Productos Terminados </span>
            <span> ".'/'.date('d-m-Y')." </span>
        </div>";
        $this->headTitle=$title;
        $products=$this->production->products()
        ->groupBy('productible_id','productible_type','unitable_id','unitable_type')
        ->selectRaw('sum(cant) as cant, productible_id, productible_type, id, unitable_id, unitable_type')
        ->with('productible','unitable');
        return $products;
    }

    public function columns()
    {
        $products=$this->builder()->get()->toArray();
        return [
            DateColumn::name('created_at')->label('Hora')->format('H:i'),
            Column::callback(['productible_type','id'], function($productible, $id) use ($products) {
                $result=arrayFind($products,'id',$id);
                return $result['productible']['name'];
            })->label('Producto'),
            Column::callback(['cant','id'], function ($cant, $id) use ($products) {
                $result=arrayFind($products,'id',$id);
                return $result['cant'];
            })->label('Cantidad'),
            Column::callback(['unitable_id','id'], function($unitable, $id) use ($products) {
                $result=arrayFind($products,'id',$id);
                return $result['unitable']['name'];
            })->label('Atributo'),
            Column::callback(['updated_at','id'], function($created, $id)  {
                return '$'. formatNumber($this->production['costUnit']);
            })->label('C. Unit.'),
            Column::callback(['productible_id', 'id'], function($cant, $id) use ($products) {
                $result=arrayFind($products,'id',$id);
                return '$'. formatNumber($this->production['costUnit']*$result['cant']);
            })->label('C. Total'),
        ];
    }
}