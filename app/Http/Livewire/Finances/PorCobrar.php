<?php

namespace App\Http\Livewire\Finances;

use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class PorCobrar extends LivewireDatatable
{
    public $padding = "px-2";
    public $headTitle = "Cuentas Por Cobrar";
    public function builder()
    {
        $place = auth()->user()->place;
        $porCobrar = $place->counts()
            ->where('balance', '>', 0)
            ->where(function ($porCobrar) {
                return $porCobrar->where('code', 'like', '101%')
                    ->orWhere('code', 'like', '102%')
                    ->orWhere('code', 'like', '103%')
                    ->orWhere('code', 'like', '105%')
                    ->orWhere('code', 'like', '106%');
            });
        return $porCobrar;
    }

    public function columns()
    {
        return [
            Column::name('code')->label('Código')->searchable(),
            Column::name('name')->label('Nombre')->searchable(),
            Column::callback('balance', function ($saldo) {
                return '$' . formatNumber($saldo);
            })->label('Saldo')->enableSummary(),
        ];
    }
    public function summarize($column)
    {

        $results = json_decode(json_encode($this->results->items()), true);
        foreach ($results as $key => $value) {
            $val = json_decode(json_encode($value), true);
            $results[$key][$column] = preg_replace("/[^0-9 .]/", '', $val[$column]);
        }
        try {
            return " 
            <div class='flex justify-between items-center'>
                <span class='font-bold uppercase '>Total</span>
                <span class='font-bold text-right'>" . '$' . formatNumber(array_sum(array_column($results, $column))) . "</span>
            </div>";
        } catch (\TypeError $e) {
            return '';
        }
    }
}
