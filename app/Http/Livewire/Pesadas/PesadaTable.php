<?php

namespace App\Http\Livewire\Pesadas;

use App\Models\Pesada;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class PesadaTable extends LivewireDatatable
{
    public $headTitle="Pesadas realizadas";
    public function builder()
    {
        $pesadas=Pesada::query();
        return $pesadas;
    }

    public function columns()
    {
        return [
            DateColumn::name('created_at')->label('Fecha')

        ];
    }
}