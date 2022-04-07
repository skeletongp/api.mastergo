<?php

namespace App\Http\Livewire\Procesos;

use App\Models\Product;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class TableProceso extends LivewireDatatable
{

    public $exportable=true;
    public $hideable='select';

    public function builder()
    {
        $procesos=auth()->user()->place->procesos()
        ->leftJoin('proceso_product_units', 'procesos.id', 'proceso_product_units.proceso_id')
        ->whereNull('deleted_at')->groupBy('procesos.id');
        return $procesos;
    }

    public function columns()
    {
        return 
        [
            Column::name('id')->defaultSort('asc')->linkTo('procesos'),
            Column::name('name')->label('Nombre')->searchable(),
            DateColumn::name('start_at')->label('Inicio')->searchable(),
            Column::raw('sum(proceso_product_units.due) AS Esperado')->label('Esperado')->searchable(),
            Column::name('status')->label('Estado')->searchable(),

        ];
    }

    public function getProductsProperty()
    {
        return Product::all();
    }
}