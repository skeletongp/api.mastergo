<?php

namespace App\Http\Livewire\Reports;

use App\Models\Bank;
use App\Models\Count;
use App\Models\Payment;
use App\Models\Provider;
use App\Models\Provision;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateOutcome extends Component
{

    public $provider_id, $counts=[], $count_code, $code_name, $ref, $amount, $concept, $discount = 0, $providers=[];
    public $efectivos=[];
    public $efectivoCode;
    public $hideButton = false, $open=false, $payAll=false;
    public $efectivo = 0, $tarjeta = 0, $transferencia = 0, $banks=[], $bank_id, $ref_bank, $tax = 0, $rate=18;
    public $setCost = true, $hideTax = true, $prov_name, $prov_rnc;

    protected $listeners=['modalOpened'];
   
    public function modalOpened()
    {
        $place = getPlace();
        $store = getStore();
        $this->providers = $store->providers()->pluck('fullname', 'id');
        $this->efectivos=
        Count::where('place_id', $place->id)
            ->whereIn('code', ['100-01','100-02'])
            ->pluck('name', 'id');
        $this->counts =
            Count::where('place_id', $place->id)
            ->select(DB::raw(' name, code'))
            ->orderBy('code')
            ->pluck('name', 'code');
        $this->banks = $store->banks()->select(DB::raw('CONCAT(bank_name," ",bank_number) AS name, id'))->pluck('name', 'id');
    }
    public function render()
    {
        if(!count($this->efectivos)){
            $this->emit('modalOpened');
        }
        return view('livewire.reports.create-outcome');
    }
    public function updatedCodeName(){
        $this->count_code=strtok($this->code_name, ' ');
    }
   /*  public function updated(){
        if(!$this->payAll){
            $this->amount=floatVal($this->efectivo)+floatVal($this->tarjeta)+floatVal($this->transferencia);
        }
    } */

    protected $rules = [
        'provider_id' => 'required',
        'amount' => 'required',
        'concept' => 'required',
        'count_code' => 'required',
        'efectivo' => 'required',
        'tarjeta' => 'required',
        'transferencia' => 'required',
        'tax' => 'required'

    ];

    public function createOutcome()
    {

        if ($this->transferencia > 0) {
            $this->rules = array_merge($this->rules, ['bank_id' => 'required']);
            $this->rules = array_merge($this->rules, ['ref_bank' => 'required']);
        }
        if ($this->efectivo > 0) {
            $this->rules = array_merge($this->rules, ['efectivoCode' => 'required']);
           
        }
        if ($this->tax > 0) {
            $this->rules = array_merge($this->rules, ['rate' => 'required']);
            $this->rules = array_merge($this->rules, ['ref' => 'required|regex:/(B)([0-9]{2})([0-9]{8})$/']);
           
        }
        if(!$this->amount){
           $this->amount=floatVal($this->efectivo)+floatVal($this->tarjeta)+floatVal($this->transferencia);
        }
        $this->validate();
        $provider = Provider::whereId($this->provider_id)->first();
        if(!$this->check($provider)){
            return;
        }
        $codeProv = Provision::LETTER[rand(0, 25)] . date('His');
        $code = $this->ref ?: $codeProv;
        $outcome = setOutcome($this->amount, $this->concept, $this->tax>0?$codeProv:$code, null, $this->tax>0?$code:null);
        if ($provider) {
            $provider->outcomes()->save($outcome);
           
        }
        $this->createPayment($outcome, $code, $provider);
        $this->emit('refreshLivewireDatatable');
        $this->reset('amount', 'efectivo', 'tarjeta', 'transferencia','concept','provider_id', 'count_code', 'bank_id', 'ref_bank', 'tax', 'ref');
        $this->emit('showAlert', 'Gasto registrado exitosamente', 'success');
    }
    public function check($provider): bool
    {
        $payed=$this->efectivo+$this->tarjeta+$this->transferencia;
        if($payed>$this->amount){
            $this->emit('showAlert', 'El monto pagado no puede ser mayor al monto del gasto', 'error',5000);
            return false;
        }
        if($payed<$this->amount && $provider && $provider->id==1){
            $this->emit('showAlert', 'Debe elegir un suplidor registrado para gasto a crédito', 'error',5000);
            return false;
        }
        return true;
    }
    public function createPayment($outcome, $code, $provider)
    {
        $payed = $this->efectivo + $this->transferencia + $this->tarjeta;
        $payer=$provider?:auth()->user();
        $data = [
            'ncf' => $outcome->ncf,
            'day' => date('Y-m-d'),
            'amount' => $this->getBruto($outcome->amount, $this->tax),
            'discount' => 0,
            'tax' => $outcome->amount - $this->getBruto($outcome->amount, $this->tax),
            'total' => $outcome->amount,
            'payed' => $payed,
            'efectivo' => $this->efectivo,
            'transferencia' => $this->transferencia,
            'tarjeta' => $this->tarjeta,
            'rest' => $outcome->amount - $payed > 0 ? $outcome->amount - $payed : 0,
            'cambio' => $payed - $outcome->amount > 0 ? $payed - $outcome->amount : 0,
            'payer_type' => $provider?Provider::class:User::class,
            'payer_id' =>$payer->id,
            'contable_type' => User::class,
            'contable_id' => auth()->user()->id,
            'place_id' => auth()->user()->place->id,
            'forma' => $payed >= $outcome->amount ? 'contado' : 'credito'
        ];
        $payment = setPayment($data);
        $outcome->payments()->save($payment);
        $outcome->update(['rest' => $payment->rest]);
        $provider->update([
            'limit' => $provider->limit - $outcome->rest
        ]);
        $this->setTransaction($payment, $code);
        $result=Payment::whereId($payment->id)->with('place','payable','payer','contable')->first();
        $result->place->preference=getPreference(getPlace()->id);
        $result->store=getStore();
        $this->emit('printPayment', $result);
    }
    public function setTransaction($payment, $code)
    {
        $place = auth()->user()->place;
        $debitable = $place->findCount($this->count_code);
        $real = 1;
        $itbis = $place->findCount('103-01');
        $hasTax=$this->tax==1?true:false;
        /* Registro de asiento sin impuestos */
            $efectivo = $place->counts()->whereId($this->efectivoCode)->first();
        setTransaction($this->concept . ' - efectivo', $code, $this->getBruto($payment->efectivo, $hasTax ), $debitable, $efectivo, 'Crear Gastos');
        setTransaction($this->concept . ' Gasto otros', $code,$this->getBruto($payment->tarjeta, $hasTax ), $debitable, $place->other(), 'Crear Gastos');
        if ($this->bank_id) {
            $bank = Bank::whereId($this->bank_id)->first();
            setTransaction($this->concept . ' Gasto por banco', $code, $this->getBruto($payment->transferencia, $hasTax ), $debitable, $bank->contable, 'Crear Gastos');
        }
        $provider = Provider::whereId($this->provider_id)->first();
        if ($provider) {
            setTransaction($this->concept . ' Gasto a crédito', $code,$this->getBruto($payment->rest, $hasTax), $debitable, $provider->contable, 'Crear Gastos');
        }

        /* Registro de impuestos */
        if($hasTax){

            setTransaction('ITBIS en efectivo', $code, $this->getTax($payment->efectivo, $hasTax ), $itbis, $efectivo, 'Crear Gastos');
            setTransaction('ITBIS otros', $code, $this->getTax($payment->tarjeta, $hasTax ), $itbis, $place->other(), 'Crear Gastos');
            if ($this->bank_id) {
                $bank = Bank::whereId($this->bank_id)->first();
                setTransaction('ITBIS por banco', $code,$this->getTax($payment->transferencia, $hasTax ), $itbis, $bank->contable, 'Crear Gastos');
            }
            setTransaction('ITBIS a crédito', $code, $this->getTax($payment->rest, $hasTax ), $itbis, $provider->contable, 'Crear Gastos');
        }
       
    }
    public function getBruto($amount, $tax=false)
    {
        if(!$tax){
            return $amount;
           }
        return $amount /(1+($this->rate/100));
    }
    public function getTax($amount, $tax=false)
    {
        return $amount - $this->getBruto($amount, true);
    }
}
