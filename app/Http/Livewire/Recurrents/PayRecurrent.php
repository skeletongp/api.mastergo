<?php

namespace App\Http\Livewire\Recurrents;

use App\Models\Bank;
use App\Models\Count;
use App\Models\Recurrent;
use Livewire\Component;

class PayRecurrent extends Component
{
    public $banks = [], $bank;
    public $recurrent, $next_date, $bank_id, $ref_bank, $rnc, $ncf;
    public $efectivo = 0.00, $tarjeta = 0.00, $transferencia = 0.00, $tax = 0.00;
    public function mount($recurrent)
    {
        $this->recurrent = $recurrent;
        $this->next_date = getNextDate($recurrent['recurrency'], $recurrent['expires_at'])->format('Y-m-d');
        $store = auth()->user()->store;
        $this->banks = $store->banks()->selectRaw('bank_name as name, id')->pluck('name', 'banks.id');
    }
    public function render()
    {
        return view('livewire.recurrents.pay-recurrent');
    }
    protected $rules = [
        'efectivo' => 'required',
        'tarjeta' => 'required',
        'transferencia' => 'required',
        'tax' => 'required',
    ];
    public function reValidate()
    {
        if ($this->transferencia > 0) {
            $this->rules = array_merge($this->rules, ['bank_id' => 'required']);
            $this->rules = array_merge($this->rules, ['ref_bank' => 'required']);
            $this->rules = array_merge($this->rules, ['bank' => 'required']);
        }
        $this->validate();
    }

    public function updatedBankId(){
        $this->bank=Bank::find($this->bank_id);
    }

    public function payRecurrent()
    {
        $this->reValidate();
        $debitable=Count::find($this->recurrent['count_id']);
        $place=auth()->user()->place;
        setTransaction('Reg. pago en efectivo '.$this->recurrent['name'],$this->rnc?:($this->ref_bank?:date('y-m-d')),$this->efectivo,$debitable, $place->cash());
        setTransaction('Reg. pago en otros '.$this->recurrent['name'],$this->rnc?:($this->ref_bank?:date('y-m-d')),$this->tarjeta,$debitable, $place->other());
        if ($this->transferencia > 0) {
            setTransaction('Reg. pago en transferencia/tarjeta '.$this->recurrent['name'],$this->rnc?:($this->ref_bank?:date('y-m-d')),$this->transferencia,$debitable, $this->bank->contable);
        }
        setTransaction('Reg. ITBIS pagado'.$this->recurrent['name'],$this->rnc?:($this->ref_bank?:date('y-m-d')),$this->tax,$debitable, $place->findCount('103-01'));
       $recurrent=Recurrent::find($this->recurrent['id']);
       $total=$this->efectivo+$this->tarjeta+$this->transferencia;
        $this->createPayment($recurrent,$total);
        $this->emit('refreshLivewireDatatable');
    }
    public function createPayment($recurrent, $total){
        $data = [
            'ncf' => $this->ncf,
            'amount' => $total-$this->tax,
            'discount' => 0,
            'total' =>  $total,
            'tax' =>  $this->tax,
            'payed' => $total,
            'rest' => 0,
            'cambio' =>  0,
            'efectivo' => $this->efectivo,
            'tarjeta' => $this->tarjeta,
            'transferencia' => $this->transferencia,
            'forma' => 'Contado'
        ];
        $recurrent->payments()->create(
            $data
        );
        $recurrent->update([
            'expires_at' => $this->next_date,
            'status'=>'Pagado'
        ]);
    }
}
