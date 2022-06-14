<?php

namespace App\Http\Livewire\Clients;

use App\Http\Helper\Universal;
use App\Models\Client;
use App\Models\User;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TableClient extends LivewireDatatable
{

    public $exportable=true;
    public $headTitle="Clientes Registrados";
    public  $hideable="select";


    public function builder()
    {
        $clients=auth()->user()->store->clients()->whereNull('deleted_at');
        return $clients;
    }

    public function columns()
    {
        $clients = $this->builder()->get()->toArray();
       
        return [
            NumberColumn::name('id')->defaultSort('asc')->headerAlignCenter(),
            Column::name("fullname")->searchable(),
            Column::name('email')->label('Correo Electrónico')->searchable()->headerAlignCenter(),
            Column::callback(['limit'], function($limit){
                return '$'. formatNumber($limit);
            })->label('Crédito')->searchable()->headerAlignCenter(),
            Column::name('phone')->label('Teléfono')->searchable()->headerAlignCenter(),
            Column::name('RNC')->label('No. Documento')->searchable()->headerAlignCenter()->hide(),
           $this->editColumn($clients),
           $this->deleteColumn($clients),
        ];
    }
    public function editColumn($clients)
    {
        if (auth()->user()->hasPermissionTo('Editar Clientes')) {
            return Column::name('created_at')->callback(['created_at','id'], function($created, $id) use ($clients) {
                $client=arrayFind($clients, 'id', $id);
                return view('pages.clients.actions', compact('client'));
            })->label('Editar')->headerAlignCenter();
        } 
    }
    public function deleteColumn($clients)
    {
        if (auth()->user()->hasPermissionTo('Borrar Clientes')) {
            return Column::delete('id')->label("Borrar");
        }
    }
    public function delete($id)
    {
        $client = Client::find($id);
        $generic=auth()->user()->store->generic;
        if ($id !== $generic->id) {
            $client->contable->delete();
            $client->delete();
        } else {
            $this->emit('showAlert', 'No puede eliminar este cliente', 'warning');
        }
    }
}