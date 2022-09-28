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

         ;
       
       return $outcomes;
    }

    public function columns()
    {
      
        return [
            DateColumn::name('outcomes.created_at')->format('d/m/Y')->label('Fecha'),
            Column::callback('users.fullname', function($user){
                return ellipsis($user, 20);
            })->label('Usuario')->searchable(),
            Column::callback('providers.fullname', function($provider){
                return ellipsis($provider, 30);
            })->label('Suplidor')->searchable(),
            NumberColumn::name('amount')->label('Monto')->formatear('money'),
            NumberColumn::name('payments.efectivo:sum')->label('Efectivo')->formatear('money')->hide(),
            NumberColumn::name('payments.transferencia:sum')->label('Transferencia')->formatear('money')->hide(),
            NumberColumn::name('payments.tarjeta:sum')->label('Otros')->formatear('money')->hide(),
            NumberColumn::name('payments.tarjeta:payed')->label('Pagado')->formatear('money'),
            NumberColumn::name('outcomes.rest')->label('Rest')->formatear('money')->hide(),
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
            })->label('Del')->contentAlignCenter()
            
        ];
    }
}