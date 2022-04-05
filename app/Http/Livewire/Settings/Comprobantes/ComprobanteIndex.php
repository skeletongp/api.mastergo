<?php

namespace App\Http\Livewire\Settings\Comprobantes;

use App\Models\Comprobante;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ComprobanteIndex extends LivewireDatatable
{
    public $exportable=true;
    public $name="Tabla de Comprobantes";
    public  $hideable="select";
    public $perPage=7;

    public function builder()
    {
        return auth()->user()->store->comprobantes()->whereNull('deleted_at');
    }

    public function columns()
    {
        return [
            NumberColumn::name('id')->defaultSort('asc'),
            Column::name('type')->label('Tipo')->searchable(),
            Column::raw("CONCAT(comprobantes.prefix,comprobantes.number) AS Serie")->searchable()->filterable(),
            Column::name('status')->label('Estado')->searchable(),
            Column::callback(['id'], function($id) {
                return view('livewire.settings.comprobantes.actions', ['comprobante'=>Comprobante::where('id', $id)->first()]);
            })->label('Acciones')->unsortable()->excludeFromExport(),
        ];
    }
}
