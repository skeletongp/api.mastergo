<?php

namespace App\Http\Livewire\Providers;

use App\Models\Provider;
use Carbon\Carbon;
use App\Http\Classes\Column;
use App\Http\Classes\NumberColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ProviderTable extends LivewireDatatable
{
    public $padding="px-2 ";
    public $headTitle="Lista de Suplidores";
    public $hideable='select';
    public function builder()
    {
        $store=auth()->user()->store;
        $providers=
        Provider::where('providers.store_id', $store->id)
        ->leftjoin('moso_master.stores', 'stores.id', '=', 'providers.store_id')
        ->rightjoin('counts', 'counts.contable_id', '=', 'providers.id')
        ->where('counts.contable_type', 'App\Models\Provider')
        ->where('counts.deleted_at', null)
        ->select('providers.*','stores.name as storeName')
        ->orderBy('providers.name', 'asc')
        ;

        return $providers;
    }



    public function columns()
    {
        return [
            Column::index($this)->label('No.')->searchable(),
            Column::name('fullname')->label('Nombre')->defaultSort(),
            Column::name('email')->label('Correo Electrónico')->searchable()->headerAlignCenter(),
            Column::name('phone')->label('Teléfono')->searchable()->headerAlignCenter(),
            Column::name('RNC')->label('No. Doc.')->searchable()->headerAlignCenter(),
            Column::raw('CONCAT(counts.code,"-",counts.name) AS count')->label('Cuenta')->searchable()->hide(),
            $this->editColumn(),
            $this->deleteColumn(),
        ];
    }
    public function editColumn()
    {
        if (auth()->user()->hasPermissionTo('Editar Clientes')) {
            return Column::name('created_at')->callback(['created_at','id'], function($created, $id)  {
                $provider_id=$id;
                return view('pages.providers.actions', compact('provider_id'));
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
