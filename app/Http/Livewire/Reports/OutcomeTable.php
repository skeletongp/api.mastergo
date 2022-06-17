<?php

namespace App\Http\Livewire\Reports;

use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class OutcomeTable extends LivewireDatatable
{
    public function builder()
    {
       $place=auth()->user()->place;
       $outcomes=$place->outcomes()->with('outcomeable','user');
       return $outcomes;
    }

    public function columns()
    {
        $outcomes=$this->builder()->get()->toArray();

        return [
            DateColumn::name('created_at')->format('d/m/Y')->label('Fecha'),
            Column::callback('amount', function($amount){
                return '$'.formatNumber($amount);
            })->label('Monto')->contentAlignRight(),
            Column::name('concepto')->label('Concepto')->searchable()
        ];
    }
}