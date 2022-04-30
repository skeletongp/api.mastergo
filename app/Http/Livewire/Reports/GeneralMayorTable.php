<?php

namespace App\Http\Livewire\Reports;

use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class GeneralMayorTable extends LivewireDatatable
{
    public $hidePagination=true;
    public $perPage=10000;
    public function builder()
    {
        $counts=auth()->user()->place->counts()->orderBy('code')->orderBy('origin')->orderBy('balance','desc')

        ->groupBy('counts.id')
        ;
        return $counts;
    }
    public function rows(){
        return [
            'Mera verga'
        ];
    }
    public function columns()
    {
        return [
            Column::index($this),
            Column::name('name')->callback(['name','code'], function($name, $code){
                return $code.'- '.$name;
            })->label('Cuenta')->searchable(),
            NumberColumn::name('debe.income:sum')->callback('debe.income:sum', function($income){
                return '$'.formatNumber($income);
            })->label('Débito')->enableSummary()->contentAlignRight(),
            Column::name('haber.outcome:sum')->callback('haber.outcome:sum', function($outcome){
                return '$'.formatNumber($outcome);
            })->label('Crédito')->enableSummary()->contentAlignRight(),
            Column::name('balance')->callback('balance', function($balance){
                return "<span class='font-bold'>".'$'.formatNumber($balance)."</span>";
            })->label('Balance')->contentAlignRight(),
        ];
    }
    public function summarize($column)
    {
        $results=json_decode(json_encode($this->results->items()), true);
        foreach ($results as $key => $value) {
            $val=json_decode(json_encode($value), true);
            $results[$key][$column]=preg_replace("/[^0-9 .]/", '', $val[$column]);
        }
        try {
           
            return "<h1 class='font-bold text-right'>". '$'.formatNumber(array_sum(array_column($results, $column)))."</h1>";;
        } catch (\TypeError $e) {
            return '';
        }
    }
}