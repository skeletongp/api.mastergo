<?php

namespace App\Http\Livewire\Recursos;

use App\Http\Helper\Universal;
use App\Models\Recurso;
use App\Models\Store;
use App\Models\Unit;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class TableRecurso extends LivewireDatatable
{
    public $exportable = true;
    public $name = "Tabla Recursos";


    public function builder()
    {
        $recursos = auth()->user()->place->recursos()->with('brands')
          ;
        return $recursos;
    }

    public function columns()
    {
        $canDelete = auth()->user()->hasPermissionTo('Borrar Recursos');
        $canEdit = auth()->user()->hasPermissionTo('Editar Recursos');
        $recursos=$this->builder()->get()->toArray();
        return [
            Column::callback(['id','uid'], function($id){
                return view('components.view', ['url'=>route('recursos.show',$id)]);
            }),
            Column::name('name')->label('Nombre')->searchable()->editable($canEdit),
            Column::callback(['created_at','id'], function($created, $id) use ($recursos){
                $result=arrayFind($recursos, 'id', $id);
                return formatNumber(array_sum(array_column($result['brands'],'cant')));
            })->label('Cant'),
            Column::callback(['updated_at','id'], function($created, $id) use ($recursos){
                $result=arrayFind($recursos, 'id', $id);
                return formatNumber(array_sum(array_column($result['brands'],'cost'))/count($result['brands']));
            })->label('Costo'),
           
            $canDelete ?
            Column::delete()->label('Eliminar') :
                Column::delete()->label('Eliminar')->hide(false)
        ];
    }

    public function getUnitsProperty()
    {
        return Unit::pluck('name');
    }
    public function getStoresProperty()
    {
        return Store::pluck('name');
    }
    public function delete($id)
    {
        Recurso::find($id)->delete();
        $this->emit('showAlert', 'Recurso borrado exitosamente', 'success');
        $this->emit('refreshLivewireDatatable');
    }
}
