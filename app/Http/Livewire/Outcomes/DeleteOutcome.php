<?php

namespace App\Http\Livewire\Outcomes;

use App\Models\Outcome;
use Livewire\Component;

class DeleteOutcome extends Component
{
    public Array $outcome;
    public $creditables, $debitables;
    public $debitableId, $creditableId;
    public function mount($outcome)
    {
        $this->outcome = $outcome;

       
    }


    public function render()
    {
        return view('livewire.outcomes.delete-outcome');
    }

    public function deleteOutcome(){
        $this->validate([
            'debitableId' => 'required',
            'creditableId' => 'required'
        ]);
        $place=auth()->user()->place;
        $outcome=Outcome::find($this->outcome['id'])->load('outcomeable','payments');
        $outcomeable=$outcome->outcomeable;
        $debitable=$place->counts()->find($this->debitableId);
        $creditable=$place->counts()->find($this->creditableId);
        setTransaction('Reversar '.$outcome->concepto, $outcome->ref, $outcome->payments->sum('payed'), $debitable, $creditable, 'Borrar Gastos');
        setTransaction('Reversar deuda '.$outcome->concepto, $outcome->ref, $outcome->rest, $outcomeable->contable, $creditable, 'Borrar Gastos');
        $outcome->delete();
        $this->emit('refreshLivewireDatatable');
    }
}
