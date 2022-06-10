<?php

namespace App\Http\Livewire\Providers;

use App\Models\Provider;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ProviderTable extends LivewireDatatable
{
    public function builder()
    {
        $store=auth()->user()->store;
        $providers=$store->providers()->with('store','provisiones');
        return $providers;
    }

    public function columns()
    {
        return [
            Column::name('fullname')->label('Nombre'),
            Column::name('email')->label('Correo Electrónico')->searchable()->headerAlignCenter(),
            Column::callback(['limit'], function($limit){
                return '$'. formatNumber($limit);
            })->label('Crédito')->searchable()->headerAlignCenter(),
            Column::name('phone')->label('Teléfono')->searchable()->headerAlignCenter(),
            Column::name('RNC')->label('No. Doc.')->searchable()->headerAlignCenter(),
        ];
    }
}