<?php

namespace App\Http\Livewire\Contables;

use App\Http\Classes\Column;
use App\Http\Classes\NumberColumn;
use App\Models\Count;
use App\Models\CountMain;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class CountView extends LivewireDatatable
{
    public $code;
    public $padding = "px-2";
    public function builder()
    {
        $count = CountMain::where('code', $this->code)->first();
        $this->headTitle = $count->name;
        $counts = Count::where('code', 'like', $this->code . '%')
            ->leftjoin('transactions as debit', 'debit.debitable_id', '=', 'counts.id')
            ->leftjoin('transactions as credit', 'credit.creditable_id', '=', 'counts.id')
        
            ->orderBy('counts.code')
            ->groupby('counts.id');
        return $counts;
    }

    public function columns()
    {
        return [
            Column::callback(['id'], function ($id) {
                return view('components.view', [
                    'url' => route('contables.counttrans', $id),
                ]);
            }),
            Column::name('code')->label('Código'),
            Column::callback([ 'name'], function ($name) {
                return ellipsis($name, 30);
            })->label('Nombre de la cuenta')->searchable(),
            Column::callback(['origin'], function($origin){
                return $origin=='debit'?"Deudor":"Acreedor";
            })->label('Origen'),
            NumberColumn::name('balance')->label('Balance')->formatear('money', 'font-bold'),



        ];
    }
}
