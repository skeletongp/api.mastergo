<?php

namespace App\Http\Livewire\Productions;

use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class ProductionCondiment extends LivewireDatatable
{
    public $production;
    public $headTitle = 'Condimentos utilizados';
    public $padding = 'px-2';
    public function builder()
    {
        $condiments=$this->production->condiments()->with('unit');
        return $condiments;
    }

    public function columns()
    {
        $condiments=$this->builder()->get()->toArray();
        return [
            Column::index($this),
            Column::callback('name', function ($name) {
                return $name;
            })->label('Nombre')->searchable(),
            Column::callback(['created_at','id'], function ($created, $id) use ($condiments) {
                $result=arrayFind($condiments, 'id', $id);
                return $result['pivot']['cant'];
            })->label('Cant.'),
            Column::callback(['unit_id','id'], function ($unit, $id) use ($condiments) {
                $result=arrayFind($condiments, 'id', $id);
                return $result['unit']['name'];
            })->label('Und.'),
            Column::callback(['updated_at','id'], function ($updated, $id) use ($condiments) {
                $result=arrayFind($condiments, 'id', $id);
                return '$'.$result['pivot']['cost'];
            })->label('Costo'),
            Column::callback(['deleted_at','id'], function ($deleted, $id) use ($condiments) {
                $result=arrayFind($condiments, 'id', $id);
                return '$'.$result['pivot']['total'];
            })->label('Total.'),
        ];
    }
}