<?php

namespace App\Http\Livewire\Banks;

use App\Models\Transaction;
use Mediconesystems\LivewireDatatables\Action;
use App\Http\Classes\Column;use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class BankDebit extends LivewireDatatable
{
    public $bank;
    public $padding = "px-2";
    public $headTitle ;
    public $uniqueDate = 'true';
    protected $queryString = [
        'page' => ['except' => 1, 'as' => 'page_credit'],
        ];
    public function builder()
    {

        $transactions = Transaction::where('debitable_id', $this->bank->contable->id);
        $this->headTitle='Ingresos a la cuenta';
        return $transactions;
    }

    public function columns()
    {
        return [
            Column::checkbox(),
            DateColumn::name('created_at')->label('Fecha')->filterable()->width('20px'),
            Column::callback('income', function ($income) {
                return '$' . formatNumber($income);
            })->label('Monto')->enableSummary()->contentAlignRight()->searchable(),
            Column::callback('concepto', function ($concept) {
                return ellipsis($concept, 25);
            })->label('Concepto')->searchable(),
            Column::name('ref')->label('Ref.')->searchable(),
            Column::callback('status', function ($status) {
                return $status == 'Pendiente' ? '<span class="fas text-red-500 fa-clock"></span>' : '<span class="fas fa-check-circle text-green-500"></span>';
            })->label('Stat')->filterable(['Pendiente', 'Confirmado'])->contentAlignCenter()->width('20px'),
        ];
    }
    public function buildActions()
    {
        return [

            Action::value('confirm')->label('Confirmar')->callback(function ($mode, $items) {
                Transaction::whereIn('id', $items)->update([
                    'status' => 'Confirmado'
                ]);
                $this->emit('refresLivewireDatatable');
            }),
            Action::value('unconfirm')->label('Desconfirmar')->callback(function ($mode, $items) {
                Transaction::whereIn('id', $items)->update([
                    'status' => 'Pendiente'
                ]);
                $this->emit('refresLivewireDatatable');
            }),



        ];
    }
    public function summarize($column)
    {

        $results=json_decode(json_encode($this->results->items()), true);
        foreach ($results as $key => $value) {
            $val=json_decode(json_encode($value), true);
            $results[$key][$column]=preg_replace("/[^0-9 .]/", '', $val[$column]);
        }
        try {

            return "<h1 class='font-bold text-right'>". '$'.formatNumber(array_sum(array_column($results, $column)))."</h1>";;
        } catch (\TypeError $e) {
            return '';
        }
    }
}
