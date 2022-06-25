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
    public $headTitle = "Recursos";


    public function builder()
    {
        $recursos = auth()->user()->place->recursos()->select()->with('brands');
        return $recursos;
    }

    public function columns()
    {

        $recursos = $this->builder()->get()->toArray();
        return [
            Column::callback(['id', 'uid'], function ($id) use ($recursos) {
                $result = arrayFind($recursos, 'id', $id);
                if (array_key_exists('brands', $result)) {
                    return view('components.view', ['url' => route('recursos.show', $id)]);
                }
                return formatNumber($result['stock']);
            })->label('Ver'),
            Column::name('name')->label('Nombre')->searchable(),
            Column::callback(['created_at', 'id'], function ($created, $id) use ($recursos) {
                $result = arrayFind($recursos, 'id', $id);
                if (array_key_exists('brands', $result)) {
                    return formatNumber(array_sum(array_column($result['brands'], 'cant')));
                }
                return formatNumber($result['stock']);
            })->label('Cant'),
            Column::callback(['updated_at', 'id'], function ($created, $id) use ($recursos) {

                $result = arrayFind($recursos, 'id', $id);
                if (array_key_exists('brands', $result)) {
                    $count= count($result['brands'])?:1;
                    return '$' . formatNumber(array_sum(array_column($result['brands'], 'cost')) / $count);
                }
                return '$' . formatNumber($result['cost']);
            })->label('Costo'),

            /*  $canDelete ?
            Column::delete()->label('Eliminar') :
                Column::delete()->label('Eliminar')->hide(false) */
        ];
    }


    public function delete($id)
    {
        Recurso::find($id)->delete();
        $this->emit('showAlert', 'Recurso borrado exitosamente', 'success');
        $this->emit('refreshLivewireDatatable');
    }
}
