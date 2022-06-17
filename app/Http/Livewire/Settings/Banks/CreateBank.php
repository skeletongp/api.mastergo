<?php

namespace App\Http\Livewire\Settings\Banks;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateBank extends Component
{
    public $form;

    protected $rules = [
        'form.bank_name' => 'required|string|max:100',
        'form.bank_number' => 'required|string|max:20|unique:banks,bank_number',
        'form.titular_id' => 'required|exists:moso_master.users,id',
    ];

    public function render()
    {
        $superAdmins = auth()->user()->store->users()
            ->role('Super Admin')
            ->select(DB::raw("CONCAT(name,' ',lastname) AS name"), 'users.id')
            ->orderBy('name')->pluck('name', 'users.id');
        $admins = auth()->user()->store->users()
            ->role('Administrador')
            ->select(DB::raw("CONCAT(name,' ',lastname) AS name"), 'users.id')
            ->orderBy('name')->pluck('name', 'users.id');
        $users = $superAdmins->union($admins);
        return view('livewire.settings.banks.create-bank', ['users' => $users]);
    }
    public function createBank()
    {
        $store=auth()->user()->store;
        $this->validate();
        $bank=$store->banks()->create($this->form);
        setContable($bank, '100', 'debit', $bank->bank_name);
        $this->emit('refreshLivewireDatatable');
        $this->emit('showAlert','Cuenta registrada','success');
        $this->reset('form');
    }
}
