<?php

use App\Models\Count;
use App\Models\CountMain;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

function setContable($model, String $code, $name = null, $place_id = null)
{
    if (!$place_id) {
        $place_id = session('place_id');
    }
    if ($model->fullname) {
        $model->name = $model->fullname;
    }
    if (!$name) {
        $name = $model->name;
    }
    $cMain = CountMain::where('code', $code)->with('counts')->first();
    $exist = $cMain->counts()->where('name', $name)->where('place_id', $place_id)->first();
    if (!$exist) {
        $cant = $cMain->counts()->where('place_id', $place_id)->count();
        $count = $cMain->counts()->create([
            'code' => $code . '-' . str_pad($cant + 1, 2, '0', STR_PAD_LEFT),
            'name' => $name,
            'place_id' => $place_id ?: 1
        ]);
        $model->contable()->save($count);
    }
}
function setTransaction($concept, $ref, $amount, $debitable, $creditable)
{
    if ($amount != 0) {
        $trans = Transaction::create([
            'concepto' => $concept,
            'ref' => $ref,
            'day' => date('Y-m-d'),
            'income' => $amount,
            'outcome' => $amount,
            'place_id' => session('place_id'),
            'debitable_id'=>$debitable->id,
            'creditable_id'=>$creditable->id,
        ]);
        if ($debitable->origin == "debit") {
            $debitable->balance = $debitable->balance + $amount;
        } else {
            $debitable->balance = $debitable->balance - $amount;
        }
        if ($creditable->origin == "credit") {
            $creditable->balance = $creditable->balance + $amount;
        } else {
            $creditable->balance = $creditable->balance - $amount;
        }
        $debitable->save();
        $creditable->save();
    }
}
 function setOutcome($amount, $concepto, $ref, $outcomeable=null, $ncf=null)
{
    $place=auth()->user()->place;
    $outcome=$place->outcomes()->create([
        'concepto'=>$concepto,
        'amount'=>$amount,
        'ncf'=>$ncf,
        'ref'=>$ref,
        'user_id'=>auth()->user()->id,
        'store_id'=>$place->store_id
    ]);
    if ($outcomeable) {
        $outcomeable->outcomes()->save($outcome);
    }
}
function setPayment($data){
    $payment=Payment::create($data);
    return $payment;
}