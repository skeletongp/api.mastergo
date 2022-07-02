<?php

namespace App\Http\Livewire\Cheques;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateCheque extends Component
{
    public $form, $banks, $persons, $person;

    public function render()
    {
        return view('livewire.cheques.create-cheque');
    }
    protected $rules = [
        'form.reference' => 'required',
        'form.amount' => 'required',
        'form.bank_id' => 'required',
        'form.type' => 'required',
        'form.chequeable_id' => 'required',
        'form.chequeable_type' => 'required',
        'person'=>'required'
    ];
    function mount()
    {
        $store=auth()->user()->store;
        $place=auth()->user()->place;
        $clients=$store->clients()->select(DB::raw("name, CONCAT(id,'|','App\\\Models\\\Client') AS id"))->pluck('name','id');
        $providers=$store->providers()->select(DB::raw("fullname as name,CONCAT(id,'|','App\\\Models\\\Provider') AS id"))->pluck('name','id');
        $users=$store->users()->select(DB::raw("fullname as name, CONCAT(users.id,'|','App\\\Models\\\User') AS id"))->pluck('name','id');
        $this->persons=$clients->merge($providers)->merge($users);
        $this->banks = $store->banks()->select(DB::raw("CONCAT(bank_number,' - ',bank_name) AS bank_number, id"))->pluck('bank_number', 'id');
    }

    public function updatedPerson()
    {
        
        $person = explode('|', $this->person);
        $this->form['chequeable_id'] = $person[0];
        $this->form['chequeable_type'] = $person[1];

    }
    function createCheque()
    {
        $this->validate();
        $this->form['user_id']=auth()->user()->id;
        $this->form['store_id']=auth()->user()->store->id;
        $this->form['status']='Pendiente';
        $place=auth()->user()->place;
        $place->cheques()->create($this->form);
        $this->reset('form','person');
        $this->emit('refreshLivewireDatatable');
        $this->emit('showAlert', 'Cheque registrado correctamente', 'success');
    }
}
