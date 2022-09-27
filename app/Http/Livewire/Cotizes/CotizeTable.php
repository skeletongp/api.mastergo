<?php

namespace App\Http\Livewire\Cotizes;

use App\Http\Classes\NumberColumn;
use App\Http\Livewire\UniqueDateTrait;
use App\Models\Cotize;
use App\Http\Classes\Column;use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class CotizeTable extends LivewireDatatable
{

    public $padding="px-2";
    public $headTitle="Cotizaciones realizadas";
    use UniqueDateTrait;
    public function builder()
    {
        $place=auth()->user()->place;
        $cotizes=Cotize::where('cotizes.place_id',$place->id)
        ->join('clients','cotizes.client_id','=','clients.id')
        ->join('moso_master.users','cotizes.user_id','=','moso_master.users.id')
        ->join('moso_master.stores','cotizes.store_id','=','moso_master.stores.id')
        ->leftjoin('details','cotizes.id','=','details.detailable_id')
        ->where('detailable_type','App\Models\Cotize')
        ->orderBy('cotizes.created_at','desc')
        ->groupby('cotizes.id');
        ;
        return $cotizes;
    }

    public function columns()
    {
        return [
            DateColumn::name('created_at')->label('Fecha'),
            Column::callback('clients.name', function($client){
                return ellipsis($client, 30);
            })->label('Cliente'),
            Column::name('users.fullname')->label('Usuario'),
            NumberColumn::name('amount')->label('Subtotal')->formatear('money'),
            NumberColumn::name('discount')->label('Descuento')->formatear('money'),
            NumberColumn::raw('SUM(details.taxtotal) AS tax')->label('Imp.')->formatear('money'),
            NumberColumn::name('total')->label('Total')->formatear('money'),
            Column::callback('id', function($id){
                return view('pages.cotizes.actions',['cotize'=>$id]);
            })->label('Acciones'),
        ];
    }
}