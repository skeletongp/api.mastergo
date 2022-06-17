<?php

namespace App\Http\Livewire\Products\Includes;

use App\Models\Bank;
use App\Models\Provider;
use App\Models\Provision;
use App\Models\User;

trait SumProductTrait
{
    public function createProvision($product, $cant, $code, $unit_id)
    {
        $product->provisions()->create([
            'code' => $code,
            'cant' => $cant,
            'atribuible_type' => Unit::class,
            'atribuible_id' => $unit_id,
            'provider_id' => $this->provider_id,
        ]);
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
        setTransaction('Compra de mercancía en efectivo', $code, $payment->efectivo, $debitable, $place->cash(), 'Sumar Productos');
        setTransaction('Compra de mercancía otros', $code, $payment->tarjeta, $debitable, $place->other(), 'Sumar Productos');
        if ($this->bank_id) {
            $bank = Bank::whereId($this->bank_id)->first();
            setTransaction('Compra de mercancía por banco', $code, $payment->transferencia, $debitable, $bank->contable, 'Sumar Productos');
        }
        $provider = Provider::whereId($this->provider_id)->first();
        setTransaction('Compra de mercancía a crédito', $code, $payment->transferencia, $debitable, $provider->contable, 'Sumar Productos');
    }
}
