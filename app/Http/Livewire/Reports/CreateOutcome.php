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

    public $provider_id, $counts, $count_code, $ref, $amount, $concept, $discount = 0, $providers;
    public $efectivoCode = '100-02', $efectivos=[];
    public $efectivo = 0, $tarjeta = 0, $transferencia = 0, $banks, $bank_id, $ref_bank, $tax = 0;
    public $setCost = true, $hideTax = true, $prov_name, $prov_rnc;
    public function mount()
    {
        $place = auth()->user()->place;
        $store = auth()->user()->store;
        $this->providers = $store->providers()->pluck('fullname', 'id');
        $this->efectivos=$place->counts()->where('code','like','100%')->pluck('name','id');
        $this->counts = $place->counts()

            ->select(DB::raw(' name, code'))
            ->orderBy('code')
            ->pluck('name', 'code');
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
        'ref' => 'required',
        'transferencia' => 'required',
        'tax' => 'required'

    ];

    public function createOutcome()
    {

        if ($this->transferencia > 0) {
            $this->rules = array_merge($this->rules, ['bank_id' => 'required']);
            $this->rules = array_merge($this->rules, ['ref_bank' => 'required']);
        }
        $this->validate();

        $code = Provision::LETTER[rand(0, 25)] . date('His');
        $code = $this->ref ?: $code;
        $provider = Provider::whereId($this->provider_id)->first();
        $outcome = setOutcome($this->amount, $this->concept, $this->ref);
        if ($provider) {
            $provider->outcomes()->save($outcome);
        }
        $this->createPayment($outcome, $code);
        $this->emit('refreshLivewireDatatable');
        $this->reset('amount', 'efectivo', 'tarjeta', 'transferencia', 'count_code', 'bank_id', 'ref_bank', 'tax', 'ref');
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
            'rest' => $outcome->amount - $payed > 0 ? $outcome->amount - $payed : 0,
            'cambio' => $payed - $outcome->amount > 0 ? $payed - $outcome->amount : 0,
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
        $tax = 0;
        $real = 1;
        $itbis = $place->findCount('103-01');
        if ($this->tax == 1) {
            $tax = 0.18;
            $real = 0.82;
        }
        /* Registro de asiento sin impuestos */
        if ($this->efectivoCode == '100-01') {
            $efectivo = $place->cash();
        } else {
            $efectivo = $place->chica();
        }
        setTransaction($this->concept . ' - efectivo', $code, $payment->efectivo * $real, $debitable, $efectivo, 'Sumar Productos');
        setTransaction($this->concept . ' Gasto otros', $code, $payment->tarjeta * $real, $debitable, $place->other(), 'Sumar Productos');
        if ($this->bank_id) {
            $bank = Bank::whereId($this->bank_id)->first();
            setTransaction($this->concept . ' Gasto por banco', $code, $payment->transferencia * $real, $debitable, $bank->contable, 'Sumar Productos');
        }
        $provider = Provider::whereId($this->provider_id)->first();
        if ($provider) {
            setTransaction($this->concept . ' Gasto a crédito', $code, $payment->rest * $real, $debitable, $provider->contable, 'Sumar Productos');
        }

        /* Registro de impuestos */
        setTransaction('ITBIS en efectivo', $code, $payment->efectivo * $tax, $itbis, $efectivo, 'Sumar Productos');
        setTransaction('ITBIS otros', $code, $payment->tarjeta * $real, $debitable, $place->other(), 'Sumar Productos');
        if ($this->bank_id) {
            $bank = Bank::whereId($this->bank_id)->first();
            setTransaction('ITBIS por banco', $code, $payment->transferencia * $tax, $itbis, $bank->contable, 'Sumar Productos');
        }
        setTransaction('ITBIS a crédito', $code, $payment->rest * $tax, $itbis, $provider->contable, 'Sumar Productos');
    }
}
