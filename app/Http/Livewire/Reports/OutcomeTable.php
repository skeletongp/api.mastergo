<?php

namespace App\Http\Livewire\Reports;

use App\Http\Classes\Column;
use App\Http\Classes\NumberColumn;
use App\Models\Outcome;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class OutcomeTable extends LivewireDatatable
{
    public $headTitle='Registro de gastos directos';
    public $padding='px-2';
    public $hideable='select';
    public function builder()
    {
       $place=getPlace();
       $outcomes=
       Outcome::where('outcomes.place_id',$place->id)
       ->leftJoin(env('DB_DATABASE_2').'.users','users.id','outcomes.user_id')
       ->leftJoin('providers','providers.id','outcomes.outcomeable_id')
       ->where('outcomeable_type','App\Models\Provider')
       ->leftJoin('payments','payments.payable_id','outcomes.id')
         ->where('payable_type','App\Models\Outcome')
        ->groupBy('outcomes.id')
         ;
       
       return $outcomes;
    }

    public function columns()
    {
      
        return [
            DateColumn::name('outcomes.created_at')->format('d/m/Y')->label('Fecha')->filterable(),
            Column::callback('users.fullname', function($user){
                return ellipsis($user, 20);
            })->label('Usuario')->searchable(),
            Column::callback('providers.fullname', function($provider){
                return ellipsis($provider, 30);
            })->label('Suplidor')->searchable(),
            NumberColumn::name('amount')->label('Monto')->formatear('money')->enableSummary(),
            NumberColumn::raw('sum(payments.efectivo) AS efectivo')->label('Efectivo')->formatear('money')->hide()->enableSummary(),
            NumberColumn::raw('sum(payments.transferencia) AS transferencia')->label('Transferencia')->formatear('money')->hide()->enableSummary(),
            NumberColumn::raw('sum(payments.tarjeta) AS tarjeta')->label('Otros')->formatear('money')->hide()->enableSummary(),
            NumberColumn::raw('sum(payments.payed) AS pagado')->label('Pagado')->formatear('money')->enableSummary(),
            NumberColumn::name('outcomes.rest')->label('Resta')->formatear('money')->enableSummary(),
            Column::callback(['outcomes.concepto'], function($concepto){
                return ellipsis($concepto, 30);
            })->label('Concepto')->searchable(),
            Column::callback('outcomes.ncf', function($ncf){
                return $ncf?:'N/A';
            })->label('NCF')->searchable(),
            Column::callback('outcomes.ref', function($ref){
                return $ref?:'N/A';
            })->label('Ref.')->searchable(),
            Column::callback(['id'], function($id){
                return view('pages.outcomes.actions',['outcome_id'=>$id]);
            })->label('Acciones')->contentAlignCenter()
            
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

            return "<h1 class='font-bold text-right'>" . '$' . formatNumber(array_sum(array_column($results, $column))) . "</h1>";;
        } catch (\TypeError $e) {
            return '';
        }
    }
}