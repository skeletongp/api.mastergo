<?php

namespace App\Http\Livewire\Procesos;

use App\Models\Product;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class TableProceso extends LivewireDatatable
{


    public function builder()
    {
        $procesos=auth()->user()->place->procesos()
        ->whereNull('deleted_at')->groupBy('procesos.id');
        return $procesos;
    }

    public function columns()
    {
        return 
        [
            Column::callback('id', function($id){
                return view('components.view', ['url'=>route('procesos.show', $id)] );
            })->label('Ver'),
            Column::name('code')->label('Código')->searchable(),
            Column::name('name')->label('Nombre')->searchable()->editable(),
           

        ];
    }

    public function getProductsProperty()
    {
        return Product::all();
    }
}