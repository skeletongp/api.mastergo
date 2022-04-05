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
    public $name="Tabla Usuarios";
    public $seacheable=['name'];
    public  $hideable="select";



    public function builder()
    {
        $clients=auth()->user()->store->clients()->whereNull('deleted_at');
        return $clients;
    }

    public function columns()
    {
        return [
            NumberColumn::name('id')->defaultSort('asc')->headerAlignCenter(),
            Column::raw("CONCAT(clients.name,' ',clients.lastname) AS Nombre Completo")->searchable(),
            Column::name('email')->label('Correo Electrónico')->searchable()->headerAlignCenter(),
            Column::callback(['limit'], function($limit){
                return '$'. Universal::formatNumber($limit);
            })->label('Crédito')->searchable()->headerAlignCenter(),
            Column::name('phone')->label('Teléfono')->searchable()->headerAlignCenter(),
            Column::name('RNC')->label('No. Documento')->searchable()->headerAlignCenter()->hide(),
            Column::callback(['id', 'name'], function ($id, $name) {
                return view('pages.clients.actions', ['client'=>Client::where('id', $id)->first()]);
            })->label('Edición')->unsortable()->excludeFromExport()->unsortable()->headerAlignCenter(),
        ];
    }
}