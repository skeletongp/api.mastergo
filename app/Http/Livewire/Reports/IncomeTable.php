<?php

namespace App\Http\Livewire\Reports;

use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class IncomeTable extends LivewireDatatable
{
    public $headTitle="Ingresos";
    public $padding="p-2";
    public function builder()
    {
        $place=auth()->user()->place;
        $incomes=$place->incomes()->with('user')->orderBy('created_at','desc');
        return $incomes;
    }

    public function columns()
    {
        $incomes=$this->builder()->get()->toArray();
        return [
            DateColumn::name('created_at')->label('Fecha')->format('d/m/Y')->searchable(),
            Column::callback('amount', function($amount){
                return '$'.formatNumber($amount);
            })->label('Monto')->contentAlignRight(),
            Column::name('concepto')->label('Concepto')->searchable()
        ];
    }
}