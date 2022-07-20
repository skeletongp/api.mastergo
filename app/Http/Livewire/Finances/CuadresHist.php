<?php

namespace App\Http\Livewire\Finances;

use Carbon\Carbon;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class CuadresHist extends LivewireDatatable
{
    public $padding = "px-2";
    public $headTitle = "Cuadre Histórico";
    public function builder()
    {
        $place = auth()->user()->place;
        $cuadres = $place->cuadres()->with('pdf')->where('day', '<', Carbon::now()->format('Y-m-d'))->orderBy('day', 'desc');
        return $cuadres;
    }

    public function columns()
    {
        $cuadres = $this->builder()->get()->toArray();
        return [
            DateColumn::name('day')->label('Fecha')->format('d/m/Y'),
            Column::callback('contado', function ($contado) {
                return '$' . formatNumber($contado);
            })->label('Contado'),
            Column::callback('credito', function ($credito) {
                return '$' . formatNumber($credito);
            })->label('Crédito'),
            Column::callback('inicial', function ($inicial) {
                return '$' . formatNumber($inicial);
            })->label('Inicial'),
            Column::callback('final', function ($final) {
                return '$' . formatNumber($final);
            })->label('Final'),
            Column::callback('id', function ($id) use ($cuadres) {
                $result = arrayFind($cuadres, 'id', $id);
                if ($result['pdf']) {
                    return '<a href="' . route('cuadres.show', $id) . 'class="btn btn-sm btn-primary">
                    <i class="fa fa-file-pdf"></i>
                    </a>';
                }
               return '<span class="fas fa-ban text-red-400"></span>';
            })->label('PDF')->contentAlignCenter(),
        ];
    }
}
