<?php

namespace App\Http\Livewire\Banks;

use App\Http\Classes\NumberColumn;
use App\Models\Bank;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class BankList extends LivewireDatatable
{
    public $headTitle="Cuentas de banco Registradas";
    public $perPage=5;
    public $padding='px-2';
    public function builder()
    {
        $store = auth()->user()->store;
        $banks = Bank::where('banks.store_id', $store->id)
        ->join('counts', 'counts.contable_id', '=', 'banks.id')
        ->where('contable_type', 'App\Models\Bank')
        ->select('banks.*', 'counts.balance');
        ;
        return $banks;
    }

    public function columns()
    {
        $store=auth()->user()->store;
        return [
            Column::index($this),
            Column::callback('bank_name', function($name){
                return ellipsis($name,35);
            })->label('Banco')->searchable(),
            Column::name('bank_number')->label('Cuenta')->searchable(),
            Column::name('currency')->label('Divisa')->filterable(['DOP','USD']),
            Column::callback(['titular','id'], function ($titular) use ($store) {
                return ellipsis($titular?:$store->name,20);
            })->label('Titular'),
            NumberColumn::name('counts.balance')->label('Balance')->formatear('money', 'font-bold'),
            Column::callback('id', function ($id) {
                return '
                <div class="flex space-x-4 items-center">
                <a  href="'.route('finances.bank_show',['bank'=>$id,'type'=>'credit']).'" class="underline">
                    <span class="fas fa-chevron-circle-down text-red-500"></span>
                </a>
                <a  href="'.route('finances.bank_show',['bank'=>$id,'type'=>'debit']).'" class="underline">
                    <span class="fas fa-chevron-circle-up text-green-500"></span>
                </a>
            </div>
                ';
            })->label('Ver')->contentAlignCenter(),
            /* Column::delete() */
        ];
    }
    public function delete($id)
    {
        $bank = $this->builder()->find($id);
        $bank->contable()->delete();
        $bank->delete();

        $this->emit('refreshLivewireDatatable');
    }
   
}
