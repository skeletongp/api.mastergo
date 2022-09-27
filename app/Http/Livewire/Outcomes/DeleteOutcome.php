<?php

namespace App\Http\Livewire\Outcomes;

use App\Models\Count;
use App\Models\Outcome;
use Livewire\Component;

class DeleteOutcome extends Component
{
    public Array $outcome;
    public $outcome_id;
    public $creditables=[], $debitables=[];
    public $debitableId, $creditableId;

    protected $listeners = ['modalOpened'];

  
    public function modalOpened(){
        $this->outcome=Outcome::find($this->outcome_id)->toArray();
        $this->debitables=
        Count::where('place_id',getPlace()->id)
        ->where('code','like','100%')
        ->pluck('name','id');
        ;
        $this->creditables=
        Count::where('place_id',getPlace()->id)
        ->pluck('name','id');
        ;
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
        if($outcome->payments->sum('tax')>0){
            $this->emit('showAlert','Este gasto tenía impuestos, así que debe ajustar los asientos debidamente', 'warning', 20000);
        } else{

            $this->emit('refreshLivewireDatatable');
        }
    }
}
