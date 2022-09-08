<?php

namespace App\Http\Livewire\Clients;

use App\Models\Transaction;
use Carbon\Carbon;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class ClientTransactions extends LivewireDatatable
{
    public $client;
    public $headTitle = "Transacciones Realizadas";
    public $padding = "px-2";
    public $hideResults = true;
    public function builder()
    {
        $contable=$this->client->contable;
        $transactions = 
       Transaction::where('debitable_id',$contable->id)
       ->orWHere('creditable_id',$contable->id)
       ->orderBy('created_at', 'desc');
        return $transactions;
    }

    public function columns()
    {
        return [
            NumberColumn::index($this)->label('#'),
            DateColumn::name('created_at')->label('Hora')->format('d/m/Y h:i A'),
            Column::callback('concepto', function ($concepto) {
                return ellipsis($concepto, 50);
            })->label('Concepto')->searchable()->filterable(
              
            ),
            Column::callback('income', function ($income) {
                return '$' . formatNumber($income);
            })->label('Monto'),



        ];
    }
}
