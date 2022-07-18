<?php

namespace App\Http\Livewire\Banks;

use Livewire\Component;

class CreateBank extends Component
{
    public $form, $banks, $users;

    protected $rules=[
        'form.bank_name'=>'required',
        'form.bank_number'=>'required|unique:banks,bank_number',
        'form.titular'=>'string|max:30',
        'form.currency'=>'required',
    ];
    public function mount(){
        $this->form=[
            'currency'=>'DOP',
        ];
        $banks=file_get_contents(public_path('banks.json'));
        $banks=json_decode($banks,true);
        $store=auth()->user()->store;
        $this->users=$store->users()->pluck('fullname');
        $this->banks=array_column($banks,'0');
        $this->form['currency']='DOP';
    }
    public function render()
    {
        return view('livewire.banks.create-bank');
    }

    public function createBank(){
        $this->validate();
        $store=auth()->user()->store;
        $bank=$store->banks()->create($this->form);
        setContable($bank,'100','debit','','',true);
        $this->reset('form');
        $this->emit('refreshLivewireDatatable');
    }
}
