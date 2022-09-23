<?php

namespace App\Http\Livewire\Contables;

use App\Http\Classes\NumberColumn as ClassesNumberColumn;
use App\Models\Count;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Http\Classes\Column;use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
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
            ->where('balance', '>', 0)
            ;
        return $counts;
    }

    public function columns()
    {
        return [
            Column::callback(['counts.name', 'counts.code', 'counts.currency'], function ($name, $code, $currency) {
                return ellipsis($code . '- ' . $name . '- ' . $currency, 38);
            })->label('Cuenta')->searchable(),
            Column::callback('origin', function($origin){
                return $origin=='debit'?'Deudor':'Acreedor';
            
            })->label('Saldo'),
            ClassesNumberColumn::raw('counts.balance')->label('Balance')->formatear('money','font-bold'),
           
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
