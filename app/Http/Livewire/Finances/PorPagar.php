<?php

namespace App\Http\Livewire\Finances;

use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class PorPagar extends LivewireDatatable
{
    public $padding="px-2";
    public $headTitle="Cuentas Por Pagar";
    public function builder()
    {
        $place=auth()->user()->place;
        $porPagar=$place->counts()
        ->where('code','like','2%')
        ->where('balance','>',0)
        ;
        return $porPagar;
    }

    public function columns()
    {
        return [
            Column::name('code')->label('Código'),
            Column::name('name')->label('Nombre'),
            Column::callback('balance', function ($saldo){
                return '$'.formatNumber($saldo);
            })->label('Saldo'),
        ];
    }
}