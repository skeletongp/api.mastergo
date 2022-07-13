<?php

namespace App\Http\Livewire\Products\Includes;

use App\Models\Bank;
use App\Models\Provider;
use App\Models\Provision;
use App\Models\Unit;
use App\Models\User;

trait SumProductTrait
{
    public $prov_name, $prov_rnc;
    public function createProvision($product, $cant, $code, $unit_id, $cost)
    {
        $place = auth()->user()->place;
      $provision=  $product->provisions()->create([
            'code' => $code,
            'cant' => $cant,
            'cost' => $cost,
            'prov_name' => $this->prov_name,
            'prov_rnc' => $this->prov_rnc,
            'atribuible_type' => Unit::class,
            'atribuible_id' => $unit_id,
            'provider_id' => $this->provider_id,
            'place_id' => $place->id,
            'user_id' => auth()->user()->id,
        ]);
        
    }
    public function print($code)
    {
        $provisions=Provision::whereCode($code)->with('provider','provisionable','atribuible', 'place.store','place.preference', 'user')->get();
        $this->emit('printProvision',$provisions);

    }
    public function createPayment($outcome, $code)
    {
        $payed = $this->efectivo + $this->transferencia + $this->tarjeta;
        if ($payed>$this->total) {
            $this->emit('showAlert','Gasto no registrado. La suma pagada es mayor que el total','error');
        }
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
    public function updatedProvRNC()
    {
        $this->loadProvFromRNC();
    }
    public function loadProvFromRNC()
    {
        if ($this->prov_rnc) {
            $url = 'contribuyentes/' . $this->prov_rnc;
            $prov = getApi($url);
            if (array_key_exists('model', $prov)) {
                $this->prov_name = $prov['model']['name'];
            }
        }
    }
    public function setTransaction($payment, $code)
    {
        $tot=$this->total?:0.00000000000001;
        $place = auth()->user()->place;
        $rTax = $this->tax / $tot;
        $rNonTax = 1 - $rTax;
        $debitable = $place->findCount($this->count_code);
        $itbisCount = $place->findCount('103-01');
        $discountable = $place->findCount('501-02');
        /* Compra en efectivo */
        setTransaction('Reg. Compra de mercancía en efectivo', $code, $payment->efectivo * $rNonTax, $debitable, $place->cash(), 'Sumar Productos');
        /* Impuestos */
        setTransaction('Reg. ITBIS gravado a compra', $code, $payment->efectivo * $rTax, $itbisCount, $place->cash(), 'Sumar Productos');

        /* Compras con tarjeta */
        setTransaction('Reg. Compra de mercancía otros', $code, $payment->tarjeta * $rNonTax, $debitable, $place->other(), 'Sumar Productos');
        /* Impuestos */
        setTransaction('Reg. ITBIS gravado a compra', $code, $payment->tarjeta * $rTax, $itbisCount, $place->other(), 'Sumar Productos');
        if ($this->bank_id) {
            $bank = Bank::whereId($this->bank_id)->first();
            /* Compra por transferencia */
            setTransaction('Reg. Compra de mercancía por banco', $code, $payment->transferencia * $rNonTax, $debitable, $bank->contable, 'Sumar Productos');
            /* Impuesto */
            setTransaction('Reg. ITBIS gravado a compra', $code, $payment->tarjeta * $rTax, $itbisCount, $bank->contable, 'Sumar Productos');
        }
        $provider = Provider::whereId($this->provider_id)->first();

        /* Compras a crédito */
        setTransaction('Reg. Compra de mercancía a crédito', $code, $payment->rest* $rNonTax, $debitable, $provider->contable, 'Sumar Productos');

        /* Impuestos */
        setTransaction('Reg. ITBIS gravado a compra', $code, $payment->rest*$rTax, $itbisCount,  $provider->contable, 'Sumar Productos');

        /* Descuentos */
        setTransaction('Reg. descuento a compra', $code, $this->discount, $debitable, $discountable, 'Sumar Productos');

    }
}
