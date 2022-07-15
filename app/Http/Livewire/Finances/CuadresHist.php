<?php

namespace App\Http\Livewire\Finances;

use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class CuadresHist extends LivewireDatatable
{
    public $padding="px-2";
    public $headTitle="Cuadre Histórico";
    public function builder()
    {
        $place=auth()->user()->place;
        $cuadres=$place->cuadres()->orderBy('day','desc');
        return $cuadres;
    }

    public function columns()
    {
        return [
            DateColumn::name('day')->label('Fecha')->format('d/m/Y'),
            Column::callback('contado', function($contado){
                return '$'.formatNumber($contado);
            })->label('Contado'),
            Column::callback('credito', function($credito){
                return '$'.formatNumber($credito);
            })->label('Crédito'),
            Column::callback('inicial', function($inicial){
                return '$'.formatNumber($inicial);
            })->label('Inicial'),
        ];
    }
}