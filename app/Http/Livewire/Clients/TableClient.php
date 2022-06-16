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
            Column::callback(['updated_at','id'], function($updated, $id) use ($clients){
                $client=arrayFind($clients, 'id', $id);
                return  view('pages.clients.link-to-invoice', ['client_id' => $client['id'], 'debt' =>'$'. formatNumber($client['debt']), 'key' => uniqid()]);
            })->label('Deuda')->headerAlignCenter(),
            Column::name('phone')->label('Teléfono')->searchable()->headerAlignCenter(),
            Column::name('RNC')->label('No. Documento')->searchable()->headerAlignCenter()->hide(),
            Column::name('created_at')->callback(['created_at','id'], function($created, $id) use ($clients) {
                $client=arrayFind($clients, 'id', $id);
                return view('pages.clients.actions', compact('client'));
            })->label('Acciones')->headerAlignCenter(),
        ];
    }
  
    
}