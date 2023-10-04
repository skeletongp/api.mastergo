<?php

namespace App\Http\Livewire\Outcomes;

use App\Models\Count;
use App\Models\Outcome;
use Livewire\Component;

class DeleteOutcome extends Component
{
    public array $outcome;
    public $outcome_id;
    public $creditables = [], $debitables = [];
    public $debitableId, $creditableId;

    protected $listeners = ['modalOpened'];


    public function modalOpened()
    {
        $this->outcome = Outcome::find($this->outcome_id)->toArray();
    }


    public function render()
    {
        return view('livewire.outcomes.delete-outcome');
    }

    public function deleteOutcome()
    {

        $outcome = Outcome::find($this->outcome['id']);
        $outcome->delete();
        $this->emit('refreshLivewireDatatable');
    }
}
