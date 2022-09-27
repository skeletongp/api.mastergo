<?php

namespace App\Http\Livewire\Contables;

use App\Http\Classes\NumberColumn;
use App\Models\Count;
use App\Models\Transaction;
use Illuminate\Support\Facades\Date;
use App\Http\Classes\Column;use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class CountTrans extends LivewireDatatable
{
    public $count_id;
    public $padding="px-2";
    public $hideable='select';

    public function builder()
    {
        $count=Count::whereId($this->count_id)->first();
        $this->headTitle=$count->name.' - $'.formatNumber($count->balance);
        $transactions=Transaction::where('debitable_id',$count->id)
        ->withTrashed()
        ->orWhere('creditable_id',$count->id)
        ->leftJoin('counts as debits', 'debits.id', '=', 'transactions.debitable_id')
        ->leftJoin('counts as credits', 'credits.id', '=', 'transactions.creditable_id')
        ->selectRaw('transactions.*, debits.name as debitable_name, credits.name as creditable_name, debits.code as debitable_code, credits.code as creditable_code, debits.id as debitable_id, credits.id as creditable_id')
        ;
        return $transactions;
    }

    public function columns()
    {
        return [
            DateColumn::name('created_at')->label('Fecha')->format('d/m/Y H:i')->searchable(),
            Column::callback('concepto', function($concepto){
                return ellipsis($concepto,30);
            })->label('Concepto'),
            Column::name('ref')->label('Referencia')->hide(),
            Column::callback(['debits.code','debits.name'], function($code, $name){
                return $code.' - '.ellipsis($name,25);
            })->label('Debe'),
            Column::callback(['credits.code','credits.name'], function($code, $name){
                return $code.' - '.ellipsis($name,25);
            })->label('Haber')->searchable(),
            Column::callback(['income','debitable_id'], function($income, $id){
                if($this->count_id!=$id){
                    $income=0;
                }
                return '$'.formatNumber($income);
            })->label('Ingreso')->contentAlignRight()->enableSummary(),
            Column::callback(['outcome','creditable_id'], function($outcome, $id){
                if($this->count_id!=$id){
                    $outcome=0;
                }
                return '$'.formatNumber($outcome);
            })->label('Egreso')->contentAlignRight()->enableSummary(),
        ];
    }
    public function summarize($column)
    {
       
        $results = json_decode(json_encode($this->results->items()), true)?:[];
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