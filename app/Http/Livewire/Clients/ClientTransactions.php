<?php

namespace App\Http\Livewire\Clients;

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
    public $uniqueDate = "";
    public function builder()
    {
        $transactions = $this->client->transactions()->with('haber', 'debe')->orderBy('created_at', 'desc');
        return $transactions;
    }

    public function columns()
    {
        $counts = $this->client->counts()->pluck('id');
        $transactions = $this->builder()->get()->toArray();
        return [
            NumberColumn::index($this)->label('#'),
            Column::callback('created_at', function ($created) {
                return Carbon::parse($created)->format('h:i A');
            })->label('Hora'),
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
