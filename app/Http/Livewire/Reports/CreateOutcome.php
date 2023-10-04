<?php

namespace App\Http\Livewire\Reports;

use App\Models\Bank;
use App\Models\Count;
use App\Models\Outcome;
use App\Models\Payment;
use App\Models\Provider;
use App\Models\Provision;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateOutcome extends Component
{

    public $provider_id, $counts = [], $count_code, $code_name, $ref, $amount, $concept, $discount = 0, $providers = [];
    public $efectivos = [];
    public $efectivoCode;
    public $hideButton = false, $open = false, $payAll = false;
    public $efectivo = 0, $tarjeta = 0, $transferencia = 0, $banks = [], $bank_id, $ref_bank, $tax = 0, $rate = 18;
    public $setCost = true, $hideTax = true, $prov_name, $prov_rnc;
    public $itbis = 0, $selectivo = 0, $propina = 0, $other = 0, $retenido = 0, $type = 2, $total = 0;
    public $products = 0, $services = 0, $date;
    public $inPercent = true;

    protected $listeners = ['modalOpened'];

    public function modalOpened()
    {
        $place = getPlace();
        $store = getStore();
        $this->providers = $store->providers()->pluck('fullname', 'id');
        $this->efectivos =
            Count::where('place_id', $place->id)
            ->whereIn('code', ['100-01', '100-02'])
            ->pluck('name', 'id');
        $this->counts =
            Count::where('place_id', $place->id)
            ->select(DB::raw(' name, code'))
            ->orderBy('code')
            ->pluck('name', 'code');
        $this->banks = $store->banks()->select(DB::raw('CONCAT(bank_name," ",bank_number) AS name, id'))->pluck('name', 'id');
        $this->date = date('Y-m-d');
    }
    public function render()
    {
        if (!count($this->efectivos)) {
            $this->emit('modalOpened');
        }
        return view('livewire.reports.create-outcome');
    }
    public function updatedCodeName()
    {
        $this->count_code = strtok($this->code_name, ' ');
    }

    public function updatedProducts()
    {
       if($this->products){
        $this->amount = $this->products + $this->services;
        $this->updated('products');
       }
    }
    public function updatedServices()
    {
        if($this->services){
            $this->amount = $this->products + $this->services;
            $this->updated('services');
        }
    }

    public function updated($field)
    {
        $amount = $this->amount;
        $total = $amount > 0 ? $amount : 0.000000001;

        if ($this->inPercent) {
            $itbis = $total * ($this->itbis / 100);
        } else {
            $itbis = $this->itbis;
        }
        $propina = $total * ($this->propina / 100);
        $other = $total * ($this->other / 100);
        $this->total = $total + $itbis + $propina + $other-($itbis* ($this->retenido / 100));

        if($this->itbis>100 && $field=='itbis'){
            $this->emit('showAlert', 'El ITBIS se registrará en monto y no en porcentaje', 'info');
        }
    }

    protected $rules = [
        'provider_id' => 'required',
        'amount' => 'required',
        'concept' => 'required',
        'count_code' => 'required',
        'efectivo' => 'required',
        'tarjeta' => 'required',
        'transferencia' => 'required',
        'tax' => 'required',
        'itbis' => 'required|numeric|min:0',
        'selectivo' => 'required|numeric|min:0',
        'propina' => 'required|numeric|min:0|max:100',
        'other' => 'required|numeric|min:0|max:100',
        'retenido' => 'required|numeric|min:0|max:100',
        'services' => 'required|numeric|min:0',
        'products' => 'required|numeric|min:0',
        'type' => 'required',
        'date' => 'required',


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
            $this->rules = array_merge($this->rules, ['ref' => 'required']);
        }
        if (!$this->amount) {
            $this->amount = floatVal($this->efectivo) + floatVal($this->tarjeta) + floatVal($this->transferencia);
        }
        $this->validate();
        $provider = Provider::whereId($this->provider_id)->first();
        if (!$this->check($provider)) {
            return;
        }
        $codeProv = Provision::LETTER[rand(0, 25)] . date('His');
        $code = $this->ref ?: $codeProv;
        $place = getPlace();
        /* $outcome = setOutcome($this->amount, $this->concept, $this->tax>0?$codeProv:$code, null, $this->tax>0?$code:null); */
        $itbis = $this->amount * ($this->itbis / 100);
        $selectivo = $this->amount * ($this->selectivo / 100);
        if($this->total < 1){
            $this->emit('showAlert', 'El monto total no puede ser 0', 'error');
            return;
        }
        $outcome = Outcome::create([
            'concepto' => $this->concept,
            'amount' => $this->total,
            'ncf' => $this->tax > 0 ? $code : null,
            'ref' => $this->tax > 0 ? $codeProv : $code,
            'user_id' => auth()->user()->id,
            'store_id' => $place->store_id,
            'place_id' => $place->id,
            'outcomeable_id' => $provider->id,
            'outcomeable_type' => Provider::class,
            'type' => $this->type,
            'itbis' => $itbis,
            'selectivo' => $selectivo,
            'propina' => $this->amount * ($this->propina / 100),
            'other' => $this->amount * ($this->other / 100),
            'retenido' => $itbis * ($this->retenido / 100),
            'products' => $this->products,
            'services' => $this->services,
            'created_at' => $this->date,
            'updated_at' => $this->date,
        ]);
        if ($provider) {
            $provider->outcomes()->save($outcome);
        }
        $this->createPayment($outcome, $code, $provider);
        $this->emit('refreshLivewireDatatable');
        $this->reset('amount', 'efectivo', 'tarjeta', 'transferencia', 'concept', 'provider_id', 'count_code', 'bank_id', 'ref_bank', 'tax', 'ref');
        $this->emit('showAlert', 'Gasto registrado exitosamente', 'success');
    }
    public function check($provider): bool
    {
        $payed = $this->efectivo + $this->tarjeta + $this->transferencia;
        if ($payed > $this->total) {
            $this->emit('showAlert', 'El monto pagado no puede ser mayor al monto del gasto', 'error', 5000);
            return false;
        }
        if ($payed < $this->amount && $provider && $provider->id == 1) {
            $this->emit('showAlert', 'Debe elegir un suplidor registrado para gasto a crédito', 'error', 5000);
            return false;
        }
        return true;
    }
    public function createPayment($outcome, $code, $provider)
    {
        $payed = $this->efectivo + $this->transferencia + $this->tarjeta;
        $payer = $provider ?: auth()->user();
        $itbis = 0;
        if ($this->itbis > 100) {
            $itbis = $this->itbis;
        } else {
            $this->amount * ($this->itbis / 100);
        }
        $selectivo = $this->amount * ($this->selectivo / 100);
        $other = $this->amount * ($this->other / 100);
        $data = [
            'ncf' => $outcome->ncf,
            'day' => date('Y-m-d'),
            'amount' => $this->amount,
            'discount' => 0,
            'tax' => $itbis + $other + $selectivo,
            'total' => $this->total,
            'payed' => $payed,
            'efectivo' => $this->efectivo,
            'transferencia' => $this->transferencia,
            'tarjeta' => $this->tarjeta,
            'rest' => $outcome->amount - $payed > 0 ? $outcome->amount - $payed : 0,
            'cambio' => $payed - $outcome->amount > 0 ? $payed - $outcome->amount : 0,
            'payer_type' => $provider ? Provider::class : User::class,
            'payer_id' => $payer->id,
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
        $result = Payment::whereId($payment->id)->with('place', 'payable', 'payer', 'contable')->first();
        $result->place->preference = getPreference(getPlace()->id);
        $result->store = getStore();
        $this->emit('printPayment', $result);
    }
    public function setTransaction($payment, $code)
    {
        $place = auth()->user()->place;
        $debitable = $place->findCount($this->count_code);
        $itbis = $place->findCount('103-01');
        $selectivo = $place->counts()->whereName('Impuesto Selectivo por Cobrar')->first();
        $hasTax = $this->tax == 1 ? true : false;
        /* Registro de asiento sin impuestos */
        $efectivo = $place->counts()->whereId($this->efectivoCode)->first();
        setTransaction($this->concept . ' - Efectivo', $code, $this->getBruto($payment->efectivo, $hasTax), $debitable, $efectivo, 'Crear Gastos');
        setTransaction($this->concept . ' -Otros', $code, $this->getBruto($payment->tarjeta, $hasTax), $debitable, $place->other(), 'Crear Gastos');
        if ($this->bank_id) {
            $bank = Bank::whereId($this->bank_id)->first();
            setTransaction($this->concept . ' -Banco', $code, $this->getBruto($payment->transferencia, $hasTax), $debitable, $bank->contable, 'Crear Gastos');
        }
        $provider = Provider::whereId($this->provider_id)->first();
        if ($provider) {
            setTransaction($this->concept . ' -Crédito', $code, $this->getBruto($payment->rest, $hasTax), $debitable, $provider->contable, 'Crear Gastos');
        }

        /* Registro de impuestos */
        if ($hasTax) {

            /* Asientos de ITBIS */
            setTransaction('ITBIS en efectivo', $code, $this->getTax($payment->efectivo, $hasTax)[0], $itbis, $efectivo, 'Crear Gastos');
            setTransaction('ITBIS otros', $code, $this->getTax($payment->tarjeta, $hasTax)[0], $itbis, $place->other(), 'Crear Gastos');
            if ($this->bank_id) {
                $bank = Bank::whereId($this->bank_id)->first();
                setTransaction('ITBIS por banco', $code, $this->getTax($payment->transferencia, $hasTax)[0], $itbis, $bank->contable, 'Crear Gastos');
            }
            setTransaction('ITBIS a crédito', $code, $this->getTax($payment->rest, $hasTax)[0], $itbis, $provider->contable, 'Crear Gastos');

            /* Asientos de Selectivo */
            setTransaction('Selectivo en efectivo', $code, $this->getTax($payment->efectivo, $hasTax)[1], $selectivo, $efectivo, 'Crear Gastos');
            setTransaction('Selectivo otros', $code, $this->getTax($payment->tarjeta, $hasTax)[1], $selectivo, $place->other(), 'Crear Gastos');
            if ($this->bank_id) {
                $bank = Bank::whereId($this->bank_id)->first();
                setTransaction('Selectivo por banco', $code, $this->getTax($payment->transferencia, $hasTax)[1], $selectivo, $bank->contable, 'Crear Gastos');
            }
            setTransaction('Selectivo a crédito', $code, $this->getTax($payment->rest, $hasTax)[1], $selectivo, $provider->contable, 'Crear Gastos');
            $ITBIS = $this->amount * ($this->itbis / 100);

            /* Asiento de retenido */
            $reten = $place->counts()->whereName('ITBIS Retenido')->first();
            setTransaction('ITBIS Retenido', $code, $ITBIS * ($this->retenido / 100), $itbis, $reten, 'Crear Gastos');
        }
    }
    public function getBruto($amount, $hasTax)
    {
        if (!$this->inPercent) {
            $itbis = $this->itbis / $this->amount;
        } else {
            $itbis = $this->itbis / 100;
        }
        $itbis = $this->amount * ($itbis);
        $other = $this->amount * ($this->other / 100);
        $propina = $this->amount * ($this->propina / 100);
        $itbisRate = $itbis / $this->total;
        $otherRate = $other / $this->total;
        $propinaRate = $propina / $this->total;
        $taxAmount = $amount * $itbisRate;
        $otherAmount = $amount * $otherRate;
        $propinaAmount = $amount * $propinaRate;
        $bruto = $amount - $taxAmount - $otherAmount - $propinaAmount;
        return $bruto;
    }
    public function getTax($amount, $tax = false)
    {
        if (!$this->inPercent) {
            $itbis = $this->itbis / $this->amount;
        } else {
            $itbis = $this->itbis / 100;
        }

        //get the part of the amount that is itbis, propina and other
        $itbis = $this->amount * ($itbis);
        $other = $this->amount * ($this->other / 100);
        $propina = $this->amount * ($this->propina / 100);
        $selectivo = $this->amount * ($this->selectivo / 100);
        $itbisRate = $itbis / $this->total;
        $selectivoRate = $selectivo / $this->total;
        $otherRate = $other / $this->total;
        $propinaRate = $propina / $this->total;
        $taxAmount = $amount * $itbisRate;
        $otherAmount = $amount * $otherRate;
        $propinaAmount = $amount * $propinaRate;
        $selectivoAmount = $amount * $selectivoRate;
        return [$taxAmount, $selectivoAmount, $otherAmount, $propinaAmount];
    }
}
