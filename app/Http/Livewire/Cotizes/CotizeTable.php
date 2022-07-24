<?php

namespace App\Http\Livewire\Cotizes;

use App\Http\Livewire\UniqueDateTrait;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class CotizeTable extends LivewireDatatable
{

    public $padding="px-2";
    public $headTitle="Cotizaciones realizadas";
    use UniqueDateTrait;
    public function builder()
    {
        $place=auth()->user()->place;
        $cotizes=$place->cotizes()
        ->join('clients','cotizes.client_id','=','clients.id')
        ->join('moso_master.users','cotizes.user_id','=','moso_master.users.id')
        ->join('moso_master.stores','cotizes.store_id','=','moso_master.stores.id')
        ->orderBy('cotizes.created_at','desc')
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
            Column::callback('amount', function($amount){
                return '$'.formatNumber($amount);
            })->label('Subtotal'),
            Column::callback('discount', function($discount){
                return '$'.formatNumber($discount);
            })->label('Descuento'),
            Column::callback('total', function($total){
                return '$'.formatNumber($total);
            })->label('Total'),
            Column::callback('id', function($id){
                return view('pages.cotizes.actions',['cotize'=>$id]);
            })->label('Acciones'),
        ];
    }
}