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

    public $headTitle = "Clientes Registrados";
    public  $hideable = "select";
    public $padding = "px-2";

    public function builder()
    {
        $clients = auth()->user()->store->clients()->with('image','contable')->whereNull('deleted_at');
        return $clients;
    }

    public function columns()
    {
        $clients = $this->builder()->get()->toArray();
        return [
            Column::callback('id', function ($id) use ($clients) {
                $result = arrayFind($clients, 'id', $id);
                if ($result['image']) {
                    return view('components.avatar', ['url'=>route('clients.show',$id),'avatar' => $result['image']['path']]);
                } else {
                    return view('components.avatar', ['url'=>route('clients.show',$id),'avatar' => env('NO_IMAGE')]);
                }
            }),
            Column::callback(["name", 'code', "id"], function ($name, $code, $id) use ($clients) {
                $result = arrayFind($clients, 'id', $id);
                if ($name) {
                    return ellipsis($code.'-'.$name, 25);
                }
                return ellipsis($result['contact']['fullname'], 25);
            })->searchable()->label('Nombre'),
            Column::name('email')->label('Correo Electrónico')->searchable()->headerAlignCenter(),
            Column::callback(['limit'], function ($limit) {
                return '$' . formatNumber($limit);
            })->label('Crédito')->searchable()->headerAlignCenter(),
            Column::callback(['updated_at', 'id'], function ($updated, $id) use ($clients) {
                $client = arrayFind($clients, 'id', $id);
                return  '$' . formatNumber($client['contable']['balance']);
            })->label('Deuda')->headerAlignCenter(),
            Column::name('phone')->label('Teléfono')->searchable()->headerAlignCenter(),
            Column::name('RNC')->label('No. Documento')->searchable()->headerAlignCenter(),
            Column::name('created_at')->callback(['created_at', 'id'], function ($created, $id) use ($clients) {
                $client = arrayFind($clients, 'id', $id);
                return view('pages.clients.actions', compact('client'));
            })->label('Acciones')->headerAlignCenter(),
        ];
    }
}
