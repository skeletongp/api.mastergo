<?php

namespace App\Http\Livewire\Productions;

use App\Models\Brand;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ProductionRecurso extends LivewireDatatable
{
    public $headTitle = 'Recursos utilizados';
    public $padding = 'px-2';
    public $production;
    public function builder()
    {
        $recursos=$this->production->recursos()->with('unit','brands');
        return $recursos;
    }

    public function columns()
    {
        $recursos=$this->builder()->get()->toArray();
        $brands=Brand::get()->toArray();
        return [
            Column::index($this),
            Column::callback('name', function ($name) {
                return $name;
            })->label('Nombre')->searchable(),
            Column::callback(['created_at','id'], function ($created, $id) use ($recursos) {
                $result=arrayFind($recursos, 'id', $id);
                return $result['pivot']['cant'];
            })->label('Cant.'),
            Column::callback(['unit_id','id'], function ($unit, $id) use ($recursos) {
                $result=arrayFind($recursos, 'id', $id);
                return $result['unit']['name'];
            })->label('Und.'),
            Column::callback(['updated_at','id'], function ($updated, $id) use ($recursos, $brands) {
                $result=arrayFind($recursos, 'id', $id);
                $brand=arrayFind($brands, 'id', $result['pivot']['brand_id']);
                return '$'.$brand['cost'];
            })->label('Costo'),
            
        ];
    }
}