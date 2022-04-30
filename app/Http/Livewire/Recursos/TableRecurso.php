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
        $recursos = auth()->user()->place->recursos()
            ->leftJoin('units', 'units.id', 'recursos.unit_id')
            ->whereNull('recursos.deleted_at');
        return $recursos;
    }

    public function columns()
    {
        $canDelete = auth()->user()->hasPermissionTo('Borrar Recursos');
        $canEdit = auth()->user()->hasPermissionTo('Editar Recursos');
        return [
            Column::callback(['id','uid'], function($id){
                return view('components.view', ['url'=>route('recursos.show',$id)]);
            }),
            Column::name('name')->label('Nombre')->searchable()->editable($canEdit),
            Column::name('description')->label('Descripción')->searchable(),
            Column::name('units.name')->label('Medida'),
            Column::name('cant')->label('Cantidad')->searchable()->name('cant')->editable($canEdit),
            Column::callback('cost', function ($cost) {
                return '$' . formatNumber($cost);
            })->label('Costo')->searchable()->name('cost')->editable(),
            Column::callback(['cant','cost'], function($cant, $cost){
                return '$'.formatNumber($cant*$cost);
            })->label('Total'),
            $canDelete ?
            Column::delete()->label('Eliminar') :
                Column::delete()->label('Eliminar')->hide(false),
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
