<?php

namespace App\Http\Livewire\Contables;

use App\Http\Classes\NumberColumn as ClassesNumberColumn;
use App\Models\Count;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class GeneralMayorTable extends LivewireDatatable
{
    public $perPage = 10;
    public $padding = 'px-2';

    public $headTitle = "Balanza de comprobación";
    public function builder()
    {
        $counts =

            Count::where('counts.place_id', auth()->user()->place->id)
            ->orderBy('code')
            ->leftJoin('transactions as haber', 'counts.id', '=', 'haber.creditable_id')
            ->leftJoin('transactions as debe', 'counts.id', '=', 'debe.debitable_id')
            ->groupBy('counts.id');
            ;
        return $counts;
    }

    public function columns()
    {
        return [
            Column::callback(['counts.name', 'counts.code', 'counts.currency'], function ($name, $code, $currency) {
                return ellipsis($code . '- ' . $name . '- ' . $currency, 30);
            })->label('Cuenta')->searchable(),
            ClassesNumberColumn::name('debe.income:sum')->label('Débito')->enableSummary()->formatear('money'),
            ClassesNumberColumn::name('haber.outcome:sum')->label('Crédito')->enableSummary()->formatear('money'),
            ClassesNumberColumn::raw('counts.balance')->label('Balance')->enableSummary()->formatear('money','font-bold'),
            /* 
            Column::name('count_main_id')->callback(['count_main_id','id'], function($countMain, $id) use ($counts){
                $count=arrayFind($counts, 'id',$id);
                return '$'.formatNumber(array_sum(array_column($count['haber'],'outcome')));
            })->label('Crédito')->enableSummary()->contentAlignRight(),
            Column::callback(['balance'], function($balance){
                return "<span class='font-bold'>".'$'.formatNumber($balance)."</span>";
            })->label('Balance ')->contentAlignRight(), */

        ];
    }
    public function summarize($column)
    {
        if ($this->perPage < 500) {
            return '';
        }
        $results = json_decode(json_encode($this->results->items()), true);
        foreach ($results as $key => $value) {
            $val = json_decode(json_encode($value), true);
            $results[$key][$column] = preg_replace("/[^0-9 .]/", '', $val[$column]);
        }
        try {

            return "<h1 class='font-bold text-right'>" . '$' . formatNumber(array_sum(array_column($results, $column))) . "</h1>";;
        } catch (\TypeError $e) {
            return '';
        }
    }
}
