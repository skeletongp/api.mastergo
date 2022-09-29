<?php

namespace App\Http\Livewire\Outcomes;

use App\Models\Bank;
use App\Models\Count;
use App\Models\Outcome;
use App\Models\Payment;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PayOutcome extends Component
{
    public $outcome_id, $outcome, $efectivos;
    public $efectivo = 0, $tarjeta = 0, $transferencia = 0, $banks=[], $bank_id, $ref_bank, $efectivoCode;

    protected $listeners = ['modalOpened'];

    protected $rules = [
        'efectivo' => 'required',
        'tarjeta' => 'required',
        'transferencia' => 'required',
    ];

    public function validateData(){
        $rules=$this->rules;
        if($this->transferencia>0){
            $rules=array_merge($this->rules, ['bank_id' => 'required', 'ref_bank' => 'required']);
        }
        if($this->efectivo>0){
            $rules=array_merge($this->rules, ['efectivoCode' => 'required']);
        }
     
        $this->validate($rules);
    }

    public function render()
    {
        return view('livewire.outcomes.pay-outcome');
    }
    public function modalOpened(){
        $place = getPlace();
        $store = getStore();
        $this->outcome=Outcome::find($this->outcome_id);
        $this->banks = $store->banks()->select(DB::raw('CONCAT(bank_name," ",bank_number) AS name, id'))->pluck('name', 'id');
        $this->efectivos=
        Count::where('place_id', $place->id)
            ->whereIn('code', ['100-01','100-02'])
            ->pluck('name', 'id');
    }
    public function payOutcome(){
        $this->validateData();
        $outcome=$this->outcome;
        $payed=$this->efectivo+$this->tarjeta+$this->transferencia;
        $payment=$this->createPayment($outcome, $payed);
        $outcome->update([
            'rest'=>$payment->rest
        ]);
        $outcome->payments()->save($payment);
        $result=Payment::whereId($payment->id)->with('place','payable','payer','contable')->first();
        $result->place->preference=getPreference(getPlace()->id);
        $result->store=getStore();
        $this->emit('printPayment', $result);
        $provider=Provider::find($outcome->outcomeable_id);
        $this->setTransaction($outcome, $provider, $payment);
        $provider->update([
            'limit'=>$provider->limit+($payment->payed-$payment->rest)
        ]);
        $this->emit('showAlert','Pago realizado exitosamente', 'success');
        $this->emit('refreshLivewireDatatable');
    }
    public function createPayment($outcome, $payed){
        $user = auth()->user();
        $payment=Payment::create([
            'ncf' => $outcome->ncf,
            'day' => date('Y-m-d'),
            'amount'=> $outcome->rest,
            'efectivo' => $this->efectivo,
            'tarjeta' => $this->tarjeta,
            'transferencia' => $this->transferencia,
            'total'=>$outcome->rest,
            'payed'=>$payed,
            'rest'=>$outcome->rest-$payed>0?$outcome->rest-$payed:0,
            'cambio'=>$payed-$outcome->rest>0?$payed-$outcome->rest:0,
            'contable_id' => $user->id,
            'contable_type' => User::class,
            'payer_id' => $outcome->outcomeable_id,
            'payer_type' => Provider::class,
            'place_id' => $outcome->place_id,
            'forma'=>'cobro'
        ]);
        return $payment;
    }
    public function setTransaction($outcome, $provider, $payment){
        $place=getPlace();
       
        $cash=$place->findCount($this->efectivoCode);
        $bank=$this->bank_id?Bank::find($this->bank_id):null;
        setTransaction('Pago cuenta en efectivo',$outcome->ncf?:$outcome->code, $this->efectivo, $provider->contable, $cash, 'Pagar Gastos');
        setTransaction('Pago cuenta por transferencia',$outcome->ncf?:$outcome->code, $this->transferencia, $provider->contable, optional($bank)->contable, 'Pagar Gastos');
        setTransaction('Pago cuenta por cheque',$outcome->ncf?:$outcome->code, $this->tarjeta, $provider->contable, $place->check(), 'Pagar Gastos');
        setTransaction('Devolución de vuelto',$outcome->ncf?:$outcome->code, $payment->cambio, $place->cash(), $provider->contable, 'Pagar Gastos');
    }
}
