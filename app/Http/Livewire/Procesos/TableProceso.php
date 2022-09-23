<?php

namespace App\Http\Livewire\Procesos;

use App\Models\Product;
use Carbon\Carbon;
use App\Http\Classes\Column;use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class TableProceso extends LivewireDatatable
{
    public $headTitle="Procesos de la empresa";
    public $padding="px-2";

    public function builder()
    {
        $procesos=auth()->user()->place->procesos()
        ->with('productions')
        ->whereNull('deleted_at')->groupBy('procesos.id');
        return $procesos;
    }

    public function columns()
    {
        $procesos=$this->builder()->get()->toArray();
        
        return 
        [
            Column::callback(['created_at','id'], function($created,$id){
                return view('components.view', ['url'=>route('procesos.show', $id)] );
            })->label('Ver')->headerAlignCenter(),
            Column::name('code')->label('Código')->searchable(),
            Column::name('name')->label('Nombre')->searchable()->editable(),
            Column::callback('created_at', function($created){
                return Carbon::parse($created)->format('d/m/Y');
            })->label('Creado en'),
            Column::callback(['code','id'], function($code, $id) use ($procesos){
                $result=arrayFind($procesos,'id',$id);
                if(!$result['productions']){
                    return 'N/D';
                  }
                return Carbon::parse(end($result['productions'])['start_at'])->format('d/m/Y');
            })->label('Últ. Prod.'),
            Column::callback(['updated_at','id'], function($updated, $id) use ($procesos){
              $result=arrayFind($procesos, 'id', $id);
              if(!$result['productions']){
                return 'N/D';
              }
              return array_sum(array_column($result['productions'], 'setted'));
            })->label('Esperados'),
            Column::callback(['deleted_at','id'], function($deleted, $id) use ($procesos){
                $result=arrayFind($procesos, 'id', $id);
                if(!$result['productions']){
                    return 'N/D';
                  }
                return array_sum(array_column($result['productions'], 'getted'));
              })->label('Obtenido'),
            Column::delete()->label('Eliminar'),
           

        ];
    }

    public function getProductsProperty()
    {
        return Product::all();
    }
}