<?php

namespace App\Http\Livewire\Contables;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class GeneralMayorTable extends LivewireDatatable
{
    public $perPage=10;
    public $padding='px-2';

    public $headTitle="Balanza de comprobación";
    public function builder()
    {
        $counts=auth()->user()->place->counts()->orderBy('code')->orderBy('origin')->orderBy('balance','desc')
        ->with('haber','debe')
        ->select(DB::raw(('name, code, id, balance,balance_real' )))
       
        ;
        return $counts;
    }
   
    public function columns()
    {
        $counts=$this->builder()->get()->toArray();
        return [
            Column::callback(['name','code','currency'], function($name, $code, $currency) use ($counts){
                return $code.'- '.$name.'- '.$currency;
            })->label('Cuenta')->searchable(),
            NumberColumn::callback('id', function($id) use ($counts){
                $count=arrayFind($counts, 'id',$id);
                return '$'.formatNumber(array_sum(array_column($count['debe'],'income')));
            })->label('Débito')->enableSummary()->contentAlignRight(),
            Column::name('count_main_id')->callback(['count_main_id','id'], function($countMain, $id) use ($counts){
                $count=arrayFind($counts, 'id',$id);
                return '$'.formatNumber(array_sum(array_column($count['haber'],'outcome')));
            })->label('Crédito')->enableSummary()->contentAlignRight(),
            Column::callback(['balance'], function($balance){
                return "<span class='font-bold'>".'$'.formatNumber($balance)."</span>";
            })->label('Balance ')->contentAlignRight(),
          
        ];
    }
    public function summarize($column)
    {
        if($this->perPage<500){
            return '';
        }
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