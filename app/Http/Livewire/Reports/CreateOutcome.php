<?php

namespace App\Http\Livewire\Reports;

use App\Models\Bank;
use App\Models\Provider;
use App\Models\Provision;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateOutcome extends Component
{

    public $provider_id, $counts, $count_code, $ref, $amount, $concept;
    public $efectivo = 0, $tarjeta = 0, $transferencia = 0, $banks, $bank_id, $ref_bank, $tax;
    public $setCost = true;
    public function mount()
    {
        $place = auth()->user()->place;
        $store = auth()->user()->store;
        $this->counts = $place->counts()->where('code', 'like', '6%')->select(DB::raw('CONCAT(code," ",name) AS name, code'))->pluck('name', 'code');
        $this->banks = $store->banks()->select(DB::raw('CONCAT(bank_name," ",bank_number) AS name, id'))->pluck('name', 'id');
    }
    public function render()
    {
        return view('livewire.reports.create-outcome');
    }

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

    /* Crear Gasto, llamado así por el wire:click del formulario incluido de otro lado */
    public function sumCant()
    {

        if ($this->transferencia > 0) {
            $this->rules = array_merge($this->rules, ['bank_id' => 'required']);
            $this->rules = array_merge($this->rules, ['ref_bank' => 'required']);
        }
        $this->validate();
        $code = Provision::LETTER[rand(0, 25)] . date('His');
        $provider = Provider::whereId($this->provider_id)->first();
        $outcome = setOutcome($this->amount, $this->concept, $this->ref);
        $provider->outcomes()->save($outcome);
        $this->createPayment($outcome, $code);
        $this->emit('refreshLivewireDatatable');
        $this->reset( 'amount','efectivo','tarjeta','transferencia','count_code','bank_id','ref_bank','tax','ref');
        $this->emit('showAlert', 'Gasto registrado exitosamente', 'success');
    }
    public function createPayment($outcome, $code)
    {
        $payed = $this->efectivo + $this->transferencia + $this->tarjeta;
        $data = [
            'ncf' => $this->ref,
            'day' => date('Y-m-d'),
            'amount' => $outcome->amount - $this->tax,
            'discount' => 0,
            'tax' => $this->tax,
            'total' => $outcome->amount,
            'payed' => $payed,
            'efectivo' => $this->efectivo,
            'transferencia' => $this->transferencia,
            'tarjeta' => $this->tarjeta,
            'rest' => $outcome->amount - $payed>0?$outcome->amount - $payed:0,
            'cambio' => $payed - $outcome->amount>0?$payed - $outcome->amount:0,
            'payer_type' => User::class,
            'payer_id' => auth()->user()->id,
            'contable_type' => User::class,
            'contable_id' => auth()->user()->id,
            'place_id' => auth()->user()->place->id,
            'forma' => $payed >= $outcome->amount ? 'contado' : 'credito'
        ];
        $payment = setPayment($data);
        $outcome->payments()->save($payment);
        $outcome->update(['rest' => $payment->rest]);
        $this->setTransaction($payment, $code);
    }
    public function setTransaction($payment, $code)
    {
        $place = auth()->user()->place;
        $debitable = $place->findCount($this->count_code);
        setTransaction('Gasto en efectivo', $code, $payment->efectivo, $debitable, $place->cash(), 'Sumar Productos');
        setTransaction('Gasto otros', $code, $payment->tarjeta, $debitable, $place->other(), 'Sumar Productos');
        if ($this->bank_id) {
            $bank = Bank::whereId($this->bank_id)->first();
            setTransaction('Gasto por banco', $code, $payment->transferencia, $debitable, $bank->contable, 'Sumar Productos');
        }
        $provider = Provider::whereId($this->provider_id)->first();
        setTransaction('Gasto a crédito', $code, $payment->transferencia, $debitable, $provider->contable, 'Sumar Productos');
    }
}
