<?php

namespace App\Http\Livewire\Comprobantes;

use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ComprobantesTable extends LivewireDatatable
{
    public $headTitle="Tabla de comprobantes";
    public function builder()
    {
       $store=auth()->user()->store;
       return $store->comprobantes()->with('invoice.payment');
    }

    public function columns()
    {
        $comprobantes=$this->builder()->get()->toArray();
       return [
           Column::callback(['prefix','ncf'], function($prefix, $ncf){
               return $ncf;
           })->label('NCF')->searchable()->filterable(),
           
           Column::name('status')->label('Estado')->filterable(),
           Column::callback('id', function($id) use ($comprobantes){
               $result=arrayFind($comprobantes, 'id', $id);
                if ($result['invoice']) {
                    return $result['invoice']['number'];
                } else {
                    return 'N/D';
                }
           })->label('Factura')->searchable(),
           Column::callback(['type','id'], function($type, $id) use ($comprobantes){
            $result=arrayFind($comprobantes, 'id', $id);
             if ($result['invoice']) {
                 return '$'.formatNumber($result['invoice']['payment']['total']);
             } else {
                 return 'N/D';
             }
        })->label('Facturado'),
        Column::callback(['number','id'], function($number, $id) use ($comprobantes){
            $result=arrayFind($comprobantes, 'id', $id);
             if ($result['invoice']) {
                 return '$'.formatNumber($result['invoice']['payment']['tax']);
             } else {
                 return 'N/D';
             }
        })->label('Impuesto')
       ];
    }
}