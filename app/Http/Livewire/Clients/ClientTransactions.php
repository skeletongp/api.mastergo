<?php

namespace App\Http\Livewire\Clients;

use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ClientTransactions extends LivewireDatatable
{
    public $client;
    public $headTitle="Transacciones Realizadas";
    public $padding="px-2";
    public function builder()
    {
        $transactions=$this->client->transactions()->with('haber','debe')->orderBy('created_at', 'desc');
        return $transactions;
    }

    public function columns()
    {
        $counts=$this->client->counts()->pluck('id');
        $transactions = $this->builder()->get()->toArray();
        return [
            DateColumn::name('created_at')->label('Hora')->format(" h:i A"),
            Column::name('concepto')->label('Concepto'),
            Column::callback('income', function($income){
                return '$' . formatNumber($income);
            })->label('Monto'),
          
            Column::callback(['debitable_id','id'], function($outcome,$id) use ($transactions, $counts){
                $result = arrayFind($transactions, 'id', $id);
                if ($counts->contains($result['debitable_id'])) {
                    return $result['debe']['code'].'-'. ellipsis($result['debe']['name'],15);
                } else {
                   return $result['haber']['code'].'-'.ellipsis($result['haber']['name'],15);
                }
            })->label('Cuenta'),

        ];
    }
}