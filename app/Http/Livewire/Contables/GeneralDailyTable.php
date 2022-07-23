<?php

namespace App\Http\Livewire\Contables;

use App\Http\Helper\Universal;
use App\Http\Livewire\UniqueDateTrait;
use App\Models\Count;
use App\Models\Transaction;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Mediconesystems\LivewireDatatables\Action;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\TimeColumn;

class GeneralDailyTable extends LivewireDatatable
{
    use AuthorizesRequests, UniqueDateTrait;
    public $perPage = 5;
   
    public $headTitle="Transacciones realizadas";
    public $padding='px-2';
    public function builder()
    {
        $place = auth()->user()->place;
        $transactions = $place->transactions()
            ->leftJoin('counts as deberes', 'deberes.id', 'transactions.debitable_id')
            ->leftJoin('counts as haberes', 'haberes.id', 'transactions.creditable_id')
            ->orderBy('created_at','desc')
            ->with('debe', 'haber');
        return $transactions;
    }

    public function columns()
    {
        return [
            Column::index($this),
            DateColumn::name('created_at')->label('Fecha')->format("d-m-y h:i A")->filterable(),
            Column::name('deberes.code')->callback(['deberes.code', 'haberes.code',], function ($cta1, $cta2) {
                return "
                <div class='leading-4'>
                <h1 >{$cta1}</h1>
                <h1 class='text-center'>-</h1>
                <h1 class='text-right'>{$cta2}</h1>
                </div>";
            })->label('Cuenta')->headerAlignCenter()
                ->exportCallback(function ($cta1, $cta2) {
                    return $cta1 . "\r " . $cta2;
                })->searchable(),
            Column::name('deberes.name')->callback(['deberes.name', 'haberes.name', 'concepto'], function ($debe, $haber, $concepto) {
                return "
                <div class='leading-4'>
                <h1 cl>{$debe}</h1>
                <h1 class='text-center'>@</h1>
                <h1 class='text-right'>{$haber}</h1>
                <h1 class='text-center font-bold pt-2 uppercase'>{$concepto}</h1>
                </div>";
            })->label('Descripción')->headerAlignCenter()->exportCallback(function ($debe, $haber, $concepto) {
                return $debe . "\r " . $haber . "\r " . $concepto;
            })->searchable(),
            Column::name('ref')->label('Referencia')->headerAlignCenter()->searchable(),
            Column::name('income')->callback('income', function ($debe) {
                return " 
                <div class='leading'>
                    <h1 class='text-left'> $" . formatNumber($debe) . "</h1>
                    <h1 class='text-center'></h2>
                    <br>
                    <br>
                </div>
                ";
            })->label('Debe')->headerAlignCenter()->exportCallback(function ($debe) {
                return  "$" . formatNumber($debe);
            })->searchable(),
            Column::name('outcome')->callback(['outcome'], function ($haber) {
                return " 
                <div class='leading'>
                    <br>
                    <h1 class='text-right mt-4'> $" . formatNumber($haber) . "</h1>
                </div>
                ";
            })->label('Haber')->headerAlignCenter()->exportCallback(function ($haber) {
                return  "$" . formatNumber($haber);;
            }),
            Column::delete(),
        ];
    }
    public function buildActions()
    {
        return [



            Action::groupBy('Exportar reporte', function () {
                return [
                    Action::value('csv')->label('Exporta CSV')->export('SalesOrders.csv'),
                    Action::value('html')->label('Exporta HTML')->export('SalesOrders.pdf'),
                    Action::value('xlsx')->label('Exporta XLSX')->export('SalesOrders.xlsx')
                ];
            }),
        ];
    }
    public function getExportStylesProperty()
    {
        return [
            '1'  => ['font' => ['bold' => true]],
            'B2' => ['font' => ['italic' => true]],
            'C'  => ['font' => ['size' => 16]],
        ];
    }

    public function getExportWidthsProperty()
    {
        return [
            'A' => 55,
            'B' => 45,
        ];
    }
    public function delete($id)
    {
        $this->authorize('Borrar Transacciones');
        $transaction = Transaction::find($id)->load('debe', 'haber');
        if (!$transaction->deleteable) {
            $this->emit('showAlert','No se puede eliminar la transacción','error');
            return;
        }
        $debe=$transaction->debe;
        $haber=$transaction->haber;
        if($debe->origin=='debit'){
            $debe->update([
                'balance'=>$debe->balance-$transaction->income,
            ]);
        } else{
            $debe->update([
                'balance'=>$debe->balance+$transaction->income,
            ]);
        }
        if ($haber->origin=='credit') {
            $haber->update([
                'balance'=>$haber->balance-$transaction->outcome,
            ]);
        } else{
            $haber->update([
                'balance'=>$haber->balance+$transaction->outcome,
            ]);
        }
       $transaction->delete();
        $this->emit('refreshLivewireDatatable');
    }
}
