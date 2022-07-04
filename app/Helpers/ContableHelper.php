<?php

use App\Models\Count;
use App\Models\CountMain;
use App\Models\Payment;
use App\Models\Place;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

function setContable($model, String $code, String $origin, $name = null, $place_id = null, $borrable=NULL)
{
    if (!$place_id) {
        $place_id = auth()->user()->place_id;
    }
    if ($model->fullname) {
        $model->name = $model->fullname;
    }
    if (!$name) {
        $name = $model->name;
    }
    $type='real';
    $subCode=substr($code,0,1);
        $nominals=['4','5','6'];
        if (in_array($subCode, $nominals)) {
           $type='nominal';
        }
    $cMain = CountMain::where('code', $code)->with('counts')->first();
    $exist = $cMain->counts()->where('name', $name)->where('place_id', $place_id)->first();
    if (!$exist) {
        $place=Place::find($place_id);
        $bCode = Count::where('code','like',$code.'%')->where('place_id', $place_id)
        ->orderBy('code','desc')->first();
        if($bCode){
            $bCode=$bCode->code;
        } else{
            $bCode='-0';
        }
        $count = $cMain->counts()->create([
            'code' => $code . '-' . str_pad(intval(Str::after($bCode, '-')) + 1, 2, '0', STR_PAD_LEFT),
            'name' => $name,
            'place_id' => $place_id ?: 1,
            'origin' => $origin,
            'type'=>$type,
            'borrable'=>$borrable,
            'store_id'=>$place->store->id
        ]);
        $model->contable()->save($count);
    }
}
function setTransaction($concept, $ref, $amount, $debitable, $creditable, $otherPermission=null)
{
    $canCreate=auth()->user()->hasPermissionTo('Registrar Asientos');
    $canOther= $otherPermission?auth()->user()->hasPermissionTo($otherPermission):false;
    if ($amount > 0 && ($canCreate || $canOther)) {
        $trans = Transaction::create([
            'concepto' => $concept,
            'ref' => $ref,
            'day' => date('Y-m-d'),
            'income' => $amount,
            'outcome' => $amount,
            'place_id' => auth()->user()->place->id,
            'debitable_id' => $debitable->id,
            'creditable_id' => $creditable->id,
        ]);
        if ($debitable->origin == "debit") {
            $bal=$debitable;
            $debitable->balance = $debitable->balance + $amount;
            $debitable->save();

        } else {
            $debitable->balance = $debitable->balance - $amount;
            $debitable->save();
        }
        if ($creditable->origin == "credit") {
            $creditable->balance = $creditable->balance + $amount;
            $creditable->save();
        } else {
            $creditable->balance = $creditable->balance - $amount;
            $creditable->save();
        }
    }
}
function setOutcome($amount, $concepto, $ref, $outcomeable = null, $ncf = null)
{
    $place = auth()->user()->place;
    $outcome = $place->outcomes()->create([
        'concepto' => $concepto,
        'amount' => $amount,
        'ncf' => $ncf,
        'ref' => $ref,
        'user_id' => auth()->user()->id,
        'store_id' => $place->store_id
    ]);
    if ($outcomeable) {
        $outcomeable->outcomes()->save($outcome);
    }
    return $outcome;
}
function setPayment($data)
{
    $payment = Payment::create($data);
    return $payment;
}
 function ajustCount($model)
    {
        $haber = $model->haber;
        $debe = $model->debe;
        $amount = $model->income;
        if ($debe->type == 'nominal') {
            if ($debe->origin == "debit") {
                $debe->balance = $debe->balance - $amount;
                $debe->save();
            } else {
                $debe->balance = $debe->balance + $amount;
                $debe->save();
            }
        }
        if ($haber->type == 'nominal') {
            if ($haber->origin == "credit") {
                $haber->balance = $haber->balance - $amount;
                $haber->save();
            } else {
                $haber->balance = $haber->balance + $amount;
                $haber->save();
            }
        }
    }