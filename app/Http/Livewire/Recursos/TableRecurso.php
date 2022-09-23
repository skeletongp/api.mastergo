<?php

namespace App\Http\Livewire\Recursos;

use App\Http\Helper\Universal;
use App\Models\Recurso;
use App\Models\Store;
use App\Models\Unit;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Classes\Column;use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class TableRecurso extends LivewireDatatable
{
    use AuthorizesRequests;

    public $headTitle = "Recursos";
    public $padding="px-2";

    public function builder()
    {
        $recursos = auth()->user()->place->recursos()->select()->with('brands');
        return $recursos;
    }

    public function columns()
    {

        $recursos = $this->builder()->get()->toArray();
        return [
            Column::callback(['id', 'code'], function ($id) use ($recursos) {
                $result = arrayFind($recursos, 'id', $id);
                if (array_key_exists('brands', $result)) {
                    return view('components.view', ['url' => route('recursos.show', $id)]);
                }
                return formatNumber($result['stock']);
            })->label('Ver'),
            Column::name('code')->label('Cód.')->searchable(),
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
            Column::delete()->label('')  
        ];
    }


    public function delete($id)
    {
        $this->authorize('Borrar Recursos');
        Recurso::find($id)->delete();
        $this->emit('showAlert', 'Recurso borrado exitosamente', 'success');
        $this->emit('refreshLivewireDatatable');
    }
}
