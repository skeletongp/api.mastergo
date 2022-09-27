<?php

namespace App\Http\Livewire\Settings\Comprobantes;

use App\Models\Comprobante;
use App\Http\Classes\Column;use Mediconesystems\LivewireDatatables\NumberColumn;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ComprobanteIndex extends LivewireDatatable
{
    public $name="Tabla de Comprobantes";
    public  $hideable="select";
    public $padding="px-2 ";

    public function builder()
    {
        return auth()->user()->store->comprobantes()->whereNull('deleted_at');
    }

    public function columns()
    {
        return [
            NumberColumn::name('id')->defaultSort('asc'),
            Column::name('type')->label('Tipo')->searchable(),
            Column::raw("CONCAT(comprobantes.prefix,comprobantes.number) AS Serie")->searchable()->filterable([
                'B01' => 'Cr. Fiscal',
                'B02' => 'De Consumo',
                'B14' => 'Reg. Especial',
                'B15' => 'Gubernamental',
                'B03' => 'Nota de Débito',
                'B04' => 'Nota de Crédito',
            ]),
            Column::name('status')->label('Estado')->searchable(),
            Column::delete()->label('Eliminar'),
        ];
    }
}
