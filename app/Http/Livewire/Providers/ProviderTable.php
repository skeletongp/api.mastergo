<?php

namespace App\Http\Livewire\Providers;

use App\Models\Provider;
use Carbon\Carbon;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ProviderTable extends LivewireDatatable
{
    public $padding="px-2 ";
    public $headTitle="Lista de Suplidores";
    public function builder()
    {
        $store=auth()->user()->store;
        $providers=$store->providers()->with('store','provisions');
        
        return $providers;
    }

    public function columns()
    {
        $providers=$this->builder()->get()->toArray();
        return [
            Column::name('fullname')->label('Nombre'),
            Column::name('email')->label('Correo Electrónico')->searchable()->headerAlignCenter(),
            Column::callback(['limit'], function($limit){
                return '$'. formatNumber($limit);
            })->label('Crédito')->searchable()->headerAlignCenter(),
            Column::name('phone')->label('Teléfono')->searchable()->headerAlignCenter(),
            Column::name('RNC')->label('No. Doc.')->searchable()->headerAlignCenter(),
            $this->editColumn($providers),
            $this->deleteColumn(),
        ];
    }
    public function editColumn($providers)
    {
        if (auth()->user()->hasPermissionTo('Editar Clientes')) {
            return Column::name('created_at')->callback(['created_at','id'], function($created, $id) use ($providers) {
                $provider=arrayFind($providers, 'id', $id);
                return view('pages.providers.actions', compact('provider'));
            })->label('Editar')->headerAlignCenter();
        } else{
            return Column::callback('address', function($address){
                return ellipsis($address, 20);
            })->label('Dirección');
        }
    }
    public function deleteColumn()
    {
        if (auth()->user()->hasPermissionTo('Borrar Clientes')) {
            return Column::delete('id')->label("Borrar");
        }else{
            return Column::callback('created_at', function($created){
                return Carbon::parse($created)->format('d/m/Y');
            })->label('Registro')->hide();
        }
    }
    public function delete($id)
    {
        $provider = Provider::find($id);
        $generic=auth()->user()->store->prov_generic;
        if ($id !== $generic->id) {
            $provider->delete();
        } else {
            $this->emit('showAlert', 'No puede eliminar este proveedor', 'warning');
        }
    }
}