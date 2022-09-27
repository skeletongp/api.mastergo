<?php

namespace App\Http\Livewire\Finances;

use App\Http\Classes\Column;use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class PorCobrar extends LivewireDatatable
{
    public $padding = "px-2";
    public $headTitle = "Cuentas Por Cobrar Clientes";
    public function builder()
    {
        $place = auth()->user()->place;
        $porCobrar = $place->counts()
            ->where('balance', '>', 0)
            ->where(function ($porCobrar) {
                return $porCobrar->where('code', 'like', '101%')
                    ->where('code', 'not like', '100%')
                    ->Where('code', 'not like', '104%')
                    ;
            });
        return $porCobrar;
    }

    public function columns()
    {
        return [
            Column::callback('contable_id', function($id) {
                return "<a href='".route('clients.show', $id)."'> <span class='fas fa-eye'></span> </a>";
            })->label('Ver'),
            Column::name('code')->label('Código')->searchable(),
            Column::callback('name', function ($name){
                return ellipsis($name, 32);
            })->label('Nombre')->searchable(),
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
