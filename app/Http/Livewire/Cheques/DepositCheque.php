<?php

namespace App\Http\Livewire\Cheques;

use App\Models\Cheque;
use App\Models\Count;
use Livewire\Component;

class DepositCheque extends Component
{
    public  $cheque=[], $cheque_id, $type;
    public $check;
    public $status, $counts=[], $count;
    public $count_id, $comment;
    public $debitable, $creditable;
    protected $listeners = ['modalOpened' => 'modalOpened'];

    public function mount()
    {
        $this->cheque=[
            'id' => $this->cheque_id,
            'reference' => '',
            'amount' => '',
            'user_id' => '',
            'bank_id' => '',
            'type' => $this->type,
            'status' => $this->status,
            'comment' => '',
            'debitable' => '',
            'creditable' => '',
        ];
        
       
    }
    public function render()
    {
        return view('livewire.cheques.deposit-cheque');
    }
    public function modalOpened()
    {
        $this->cheque = Cheque::find($this->cheque_id)->load('user','bank','chequeable')->toArray();
        $place = auth()->user()->place;
        $this->check = $place->check();
      
        $this->updatedStatus();

    }
    public function updatedStatus()
    {
        $place = auth()->user()->place;
        if ($this->cheque['type'] == 'Recibido') {
            $this->getRecibidoCounts($place);
        } else if ($this->cheque['type'] == 'Emitido') {
            $this->getEmitidoCounts($place);
        }
    }
    public function getRecibidoCounts($place)
    {
        if ($this->status) {
            $this->counts = $place->counts()
                ->where('code', 'like', '100%')
                ->selectRaw('CONCAT(code, " - ", name) as name, counts.id')->pluck('name', 'counts.id');
        } else {
            $this->counts = $place->counts()
                ->where('code', 'like', '10%')
                ->where('code', 'not like', '100%')
                ->where('code', 'not like', '104%')
                ->selectRaw('CONCAT(code, " - ", name) as name, counts.id')->pluck('name', 'counts.id');
        }
    }

    public function getEmitidoCounts($place)
    {
        if (!$this->status) {
            $this->counts = $place->counts()
                ->where('code', 'like', '100%')
                ->selectRaw('CONCAT(code, " - ", name) as name, counts.id')->pluck('name', 'counts.id');
        }
        $this->render();
    }

    public function depositCheque()
    {

        $cheque = Cheque::find($this->cheque['id']);
        if ($this->cheque['type'] == 'Recibido' || !$this->status) {
            $this->validate([
                'count_id' => 'required',
                'comment' => 'required',
            ]);
            $this->count = Count::find($this->count_id);
            if ($this->cheque['type'] == 'Recibido') {
                $this->debitable = $this->count;
                $this->creditable = $this->check;
            } else if (optional($cheque->chequeable)->contable) {
                $this->debitable = $this->count;
                $this->creditable = $cheque->chequeable->contable;
            } else{
                $this->emit('showAlert','No se puede depositar el cheque, no se encuentra en una cuenta valida', 'error');
                return;
            }
            setTransaction($this->comment, $this->cheque['reference'], $this->cheque['amount'], $this->debitable, $this->creditable);
        }
        if ($this->status) {
            $cheque->update(['status' => 'Pago']);
        } else {
            $cheque->update(['status' => 'Cancelado']);
        }

        $this->emit('refreshLivewireDatatable');
    }
    
}
