<?php

namespace App\Http\Livewire\Reports;

use App\Http\Classes\Column;use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class OutcomeTable extends LivewireDatatable
{
    public $headTitle='Registro de gastos directos';
    public $padding='px-2';
    public $hideable='select';
    public function builder()
    {
       $place=auth()->user()->place;
       $outcomes=$place->outcomes()->with('outcomeable','user','payment', 'payments')
       ->orderBy('created_at', 'desc');
       return $outcomes;
    }

    public function columns()
    {
        $outcomes=$this->builder()->get()->toArray();
        $place=auth()->user()->place;
        $debitables=$place->counts()->where('code','like','100%')->pluck('name','id');
        $creditables=$place->counts()->pluck('name','id');
        return [
            DateColumn::name('created_at')->format('d/m/Y')->label('Fecha'),
            Column::callback(['user_id','id'], function($user,$id) use($outcomes){ 
                $result=arrayFind($outcomes,'id',$id);
                return ellipsis($result['user']['fullname'],20);
            })->label('Responsable')->contentAlignRight(),

            Column::callback(['outcomeable_id','id'], function($outcomeable_id,$id) use($outcomes){ 
                $result=arrayFind($outcomes,'id',$id);
                if($result['outcomeable']){
                    return ellipsis($result['outcomeable']['fullname'],20);
                }
                return 'N/D';
            })->label('Acreedor'),

            Column::callback('amount', function($amount){
                return '$'.formatNumber($amount);
            })->label('Monto')->contentAlignRight(),

            Column::callback(['amount','id'], function($amount, $id) use($outcomes){
                $result=arrayFind($outcomes,'id',$id);
                return '$'.formatNumber(array_sum(array_column($result['payments'],'efectivo')));
            })->label('Efectivo')->contentAlignRight()->hide(),

            Column::callback(['created_at','id'], function($amount, $id) use($outcomes){
                $result=arrayFind($outcomes,'id',$id);
                return '$'.formatNumber(array_sum(array_column($result['payments'],'transferencia')));
            })->label('Transf.')->contentAlignRight()->hide(),

            Column::callback(['concepto','id'], function($amount, $id) use($outcomes){
                $result=arrayFind($outcomes,'id',$id);
                return '$'.formatNumber(array_sum(array_column($result['payments'],'tarjeta')));
            })->label('Tarjeta')->contentAlignRight()->hide(),

            Column::callback(['updated_at','id'], function($amount, $id) use($outcomes){
                $result=arrayFind($outcomes,'id',$id);
                return '$'.formatNumber(array_sum(array_column($result['payments'],'payed')));
            })->label('Pagado')->contentAlignRight(),
            Column::callback('rest', function($rest){
                return '$'.formatNumber($rest);
            })->label('Resta')->contentAlignRight(),
            Column::name('concepto')->label('Concepto')->searchable(),
            Column::callback('ncf', function($ncf){
                return $ncf?:'N/A';
            })->label('NCF')->searchable(),
            Column::callback('ref', function($ref){
                return $ref?:'N/A';
            })->label('Ref.')->searchable(),
            Column::callback(['ref','id'], function($amount, $id) use($outcomes, $debitables, $creditables){
                $result=arrayFind($outcomes,'id',$id);
                return view('pages.outcomes.actions',['outcome'=>$result,'debitables'=>$debitables,'creditables'=>$creditables]);
            })->label('Del')->contentAlignCenter(),
        ];
    }
}