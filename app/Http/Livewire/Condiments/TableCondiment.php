<?php

namespace App\Http\Livewire\Condiments;

use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class TableCondiment extends LivewireDatatable
{
    public $headTitle = "Condimentos";
    public $padding="px-2";
    public function builder()
    {
        $condiments = auth()->user()->place->condiments()->select()->with('unit');

        return $condiments;
    }

    public function columns()
    {
        $condiments = $this->builder()->get()->toArray();
        return [
           Column::index($this),
            Column::name('name')->label('Nombre')->searchable(),
            Column::callback(['created_at', 'id'], function ($created, $id) use ($condiments) {
                $result = arrayFind($condiments, 'id', $id);
               
                return formatNumber($result['stock']);
            })->label('Cant'),
            Column::callback(['updated_at', 'id'], function ($created, $id) use ($condiments) {

                $result = arrayFind($condiments, 'id', $id);
              
                return '$' . formatNumber($result['cost']);
            })->label('Costo'),
        ];

        
    }
}