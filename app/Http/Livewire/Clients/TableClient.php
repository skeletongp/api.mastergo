<?php

namespace App\Http\Livewire\Clients;

use App\Http\Classes\NumberColumn as ClassesNumberColumn;
use App\Http\Helper\Universal;
use App\Models\Client;
use App\Models\User;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use App\Http\Classes\Column;use Mediconesystems\LivewireDatatables\NumberColumn;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TableClient extends LivewireDatatable
{

    public $headTitle = "Clientes Registrados";
    public  $hideable = "select";
    public $padding = "px-2";

    public function builder()
    {
        $clients = auth()->user()->store->clients()->whereNull('deleted_at');
        return $clients;
    }

    public function columns()
    {
        return [
            Column::callback('id', function ($id)  {
               return view('components.view', ['url' => route('clients.show', $id)]);
            }),
            Column::callback(["name", 'code'], function ($name, $code)  {
                return ellipsis($code.' - '.$name, 25);
            })->searchable()->label('Nombre'),
            Column::name('email')->label('Correo Electrónico')->searchable()->headerAlignCenter(),
            Column::callback(['limit'], function ($limit) {
                return '$' . formatNumber($limit);
            })->label('Crédito')->searchable()->headerAlignCenter(),
            ClassesNumberColumn::name('debt')->label('Deuda')->formatear('money'),
            Column::name('phone')->label('Teléfono')->searchable()->headerAlignCenter(),
            Column::name('RNC')->label('No. Documento')->searchable()->headerAlignCenter(),
            Column::callback(['id'], function ($id) {
                $client_id= $id;
                return view('pages.clients.actions', compact('client_id'));
            })->label('Acciones')->headerAlignCenter(),
        ];
    }
}
