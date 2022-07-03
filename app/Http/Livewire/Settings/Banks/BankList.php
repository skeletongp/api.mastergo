<?php

namespace App\Http\Livewire\Settings\Banks;

use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class BankList extends LivewireDatatable
{
    public $headTitle="Cuentas de Registradas";
    public $perPage=5;
    public function builder()
    {
        $store = auth()->user()->store;
        $banks = $store->banks()->with('titular','contable');
        return $banks;
    }

    public function columns()
    {
        $banks=$this->builder()->get()->toArray();
        return [
            Column::index($this),
            Column::name('bank_name')->label('Banco')->editable()->searchable(),
            Column::name('bank_number')->label('Cuenta')->searchable(),
            Column::name('id')->callback('id', function ($id)  use($banks) {
                $result = arrayFind($banks, 'id', $id);
                return $result['titular']['fullname'];
            })->label('Titular'),
            Column::name('deleted_at')->callback(['deleted_at','id'], function ($deleted, $id)  use($banks) {
                $result = arrayFind($banks, 'id', $id);
                return '<b>$'.formatNumber($result['contable']['balance']).'</b>';
            })->label('Balance')->contentAlignRight(),
        ];
    }
   
}
