<?php

namespace App\Http\Livewire\Banks;

use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class BankList extends LivewireDatatable
{
    public $headTitle="Cuentas de Registradas";
    public $perPage=5;
    public $padding='px-2';
    public function builder()
    {
        $store = auth()->user()->store;
        $banks = $store->banks()->with('store','contable');
        return $banks;
    }

    public function columns()
    {
        $banks=$this->builder()->get()->toArray();
        return [
            Column::index($this),
            Column::callback('bank_name', function($name){
                return ellipsis($name,35);
            })->label('Banco')->searchable(),
            Column::name('bank_number')->label('Cuenta')->searchable(),
            Column::name('currency')->label('Divisa')->filterable(['DOP','USD']),
            Column::callback(['created_at','id'], function ($created, $id)  use($banks) {
                $result = arrayFind($banks, 'id', $id);
                return $result['titular']?:ellipsis($result['store']['name'],20);
            })->label('Titular'),
            Column::name('deleted_at')->callback(['deleted_at','id'], function ($deleted, $id)  use($banks) {
                $result = arrayFind($banks, 'id', $id);
                return '<b>$'.formatNumber($result['contable']['balance']).'</b>';
            })->label('Balance'),
            Column::delete()
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
