<?php

namespace App\Http\Livewire\Outcomes;

use App\Models\Outcome;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Newcreditoutcome extends Component
{
    public $credit, $outcome, $outncf, $total, $itbis;
    public function render()
    {
        return view('livewire.outcomes.newcreditoutcome');
    }

    public function modalOpened()
    {
        $this->credit = [
            "selectivo" => 0,
            "propina" => 0,
            "user_id" => auth()->user()->id,
            'place_id' => auth()->user()->place->id,
            "modified_at" => Carbon::now()->format('Y-m-d'),
        ];
    }

    public function updatedOutncf()
    {

        $out = Outcome::where('ncf', $this->outncf)->with('outcomeable.contable')->first();
        if ($out) {
            $this->outcome = $out;
            $this->total = formatNumber($out->amount);
            $this->itbis = formatNumber($out->itbis);
            $this->render();
        } else {
            $this->total = null;
            $this->itbis = null;
        }
        $this->render();
    }

    public function createCreditnote()
    {
        $this->credit['modified_ncf'] = $this->outncf;
        $this->validate([
            'total' => 'required',
            'credit.modified_ncf' => 'required',
            'credit.modified_at' => 'required',
            'credit.comment' => 'required',
            'credit.itbis' => 'required|numeric|min:0',
            'credit.amount' => 'required|numeric|min:1|lte:outcome.amount|gte:credit.itbis',
        ]);
        DB::beginTransaction();
        try {
            $totalCredits = $this->outcome->credits()->sum('amount');
            $payment = $this->outcome->payments->last();

            if ($totalCredits + $this->credit["amount"] > $payment->total) {
                $this->emit('showAlert', 'El monto sobrepasa el gasto', 'arror', 4500);
                return;
            }
            $this->outcome->credits()->create(
                $this->credit
            );

            if ($this->outcome->rest >= $this->credit['amount']) {
                $payment->update([
                    'rest' => $payment->rest - $this->credit['amount'],

                ]);
                $this->outcome->update([
                    'rest' => $this->outcome->rest - $this->credit['amount'],
                ]);
            } else {
                $payment->update([
                    'cambio' => $payment->cambio + $this->credit['amount'],
                ]);
            }

            $this->setCreditTransaction($this->credit['amount'], $this->credit['itbis']);
            DB::commit();
            $this->emit('showAlert', 'Nota de crédito creada con éxito', 'success');
            $this->emit('refreshLivewireDatatable');
            $this->render();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function setCreditTransaction($mount, $itbis)
    {
        $place = auth()->user()->place;
        $desc_dev_compras = $place->findCount('501-02');
        $cash = $place->findCount('100-01');
        $itbis = $place->findCount('103-01');
        $provider = $this->outcome->outcomeable->contable;
        if ($this->outcome->rest >= $mount) {
            setTransaction('Dev. Nota de crédito compra', $this->credit['modified_ncf'], $mount - $this->credit['itbis'],  $provider, $desc_dev_compras,'Crear Gastos');
            setTransaction('Dev. Nota de crédito ITBIS', $this->credit['modified_ncf'], $this->credit['itbis'],  $provider, $itbis, 'Crear Gastos');
        } else {
            setTransaction('Dev. Nota de crédito', $this->credit['modified_ncf'], $mount - $this->credit['itbis'],  $cash, $desc_dev_compras, 'Crear Gastos');
            setTransaction('Dev. Nota de crédito ITBIS', $this->credit['modified_ncf'], $this->credit['itbis'],  $cash, $itbis, 'Crear Gastos');
        }
    }
}
