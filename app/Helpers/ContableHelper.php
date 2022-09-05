<?php

use App\Models\Bank;
use App\Models\Comprobante;
use App\Models\Count;
use App\Models\CountMain;
use App\Models\Payment;
use App\Models\Place;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

function setContable($model, String $code, String $origin, $name = null, $place_id = null, $borrable = NULL)
{
    if (!$place_id) {
        $place_id = auth()->user()->place_id;
    }
    if ($model->fullname) {
        $model->name = $model->fullname;
    }
    if ($model->bank_name) {
        $model->name = $model->bank_name;
    }
    if (!$name) {
        $name = $model->name;
    }
    $type = 'real';
    $subCode = substr($code, 0, 1);
    $nominals = ['4', '5', '6'];
    if (in_array($subCode, $nominals)) {
        $type = 'nominal';
    }
    $cMain = CountMain::where('code', $code)->with('counts')->first();
    $exist = $cMain->counts()->where('name', $name)->where('place_id', $place_id)->first();
    if (!$exist) {
        $place = Place::find($place_id);
        $bCode = Count::where('code', 'like', $code . '%')->where('place_id', $place_id)
            ->orderBy('code', 'desc')->first();
        if ($bCode) {
            $bCode = $bCode->code;
        } else {
            $bCode = '-0';
        }
        $count = $cMain->counts()->create([
            'code' => $code . '-' . str_pad(intval(Str::after($bCode, '-')) + 1, 2, '0', STR_PAD_LEFT),
            'name' => $name,
            'place_id' => $place_id ?: 1,
            'origin' => $origin,
            'type' => $type,
            'borrable' => $borrable,
            'currency' => $model->currency ?: 'DOP',
            'store_id' => $place->store->id
        ]);
        $model->contable()->save($count);
    }
}
function setTransaction($concept, $ref, $amount, $debitable, $creditable, $otherPermission = null)
{
    $user = auth()->user() ?: User::find(1);
    $canCreate = $user->hasPermissionTo('Registrar Asientos');
    $income = $amount;
    $status = 'Confirmado';

    if ($debitable && substr($debitable->code, 0, 3) == '100' && $debitable->id > 7) {

        $status = 'Pendiente';
    }
    $outcome = $amount;
    if ($debitable == $creditable) {
        return;
    }
    $canOther = $otherPermission ? $user->hasPermissionTo($otherPermission) : false;
    if ($amount > 0 && ($canCreate || $canOther) && $debitable && $creditable) {
        $trans = Transaction::create([
            'concepto' => $concept,
            'ref' => $ref,
            'day' => date('Y-m-d'),
            'income' => $income,
            'outcome' => $outcome,
            'place_id' => $user->place->id,
            'debitable_id' => $debitable->id,
            'creditable_id' => $creditable->id,
            'status' => $status,
        ]);
        if ($debitable->origin == "debit") {
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
        $debitable->touch();
        $creditable->touch();
        return $trans;
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
    Log::info('Ajustando contable ' . $debe->name);

    if ($debe->type == 'nominal') {
        $debe->balance = 0;
        $debe->save();
    }
    if ($haber->type == 'nominal') {
        $haber->balance = 0;
        $haber->save();
    }
}
function getResults()
{
    $place = auth()->user()->place;
    $ingresos =
        $ventas = $place->counts()->where('code', 'like', '400%')->sum('balance');
    $devoluciones = $place->findCount('401-01')->balance;
    $notas_credito = $place->findCount('401-02')->balance;
    $descuentos = $place->findCount('401-03')->balance;
    $otros_ingresos = $place->findCount('402-01')->balance;
    $ingresos = $ventas + $otros_ingresos - $devoluciones - $notas_credito - $descuentos;

    $devolucionCompras = $place->findCount('501-01')->balance;
    $descuentosCompras = $place->findCount('501-02')->balance;
    $costos_ventas = $place->findCount('500-01')->balance;
    $costos_servicios = $place->counts()->whereCode('500-02')->sum('balance');
    $costos_totales = $place->counts()->where('code', 'like', '500%')->sum('balance') - $devolucionCompras - $descuentosCompras;
    $otros_costos = $costos_totales - $costos_ventas - $costos_servicios + $devolucionCompras + $descuentosCompras;
    $utilidad = $ingresos - $costos_ventas - $costos_servicios - $otros_costos + $devolucionCompras + $descuentosCompras;
    $gastos_admin = $place->counts()->where('code', 'like', '600%')->sum('balance');
    $gastos_ventas = $place->counts()->where('code', 'like', '601%')->sum('balance');
    $gastos_financieros = $place->counts()->where('code', 'like', '602%')->sum('balance');
    $utilidad_antes_impuestos = $utilidad - $gastos_ventas - $gastos_admin - $gastos_financieros;

    $capital = $place->counts()->where('code', 'like', '3%')->sum('balance');
    $activo = $place->counts()->where('code', 'like', '1%')->sum('balance');
    $pasivo = $place->counts()->where('code', 'like', '2%')->sum('balance');
    $utilidad_neta = $utilidad_antes_impuestos * 0.73;
    $isr = $utilidad_neta * 0.27;
    $capital = $place->findCount('300-01');
    $isr_por_pagar = $place->findCount('203-02');
    $capital->update(['balance' => $capital->balance + $utilidad_neta]);
    if ($isr > 0) {
        $isr_por_pagar->update(['balance' => $isr_por_pagar->balance + $isr]);
    }
    Artisan::call('model:prune');
}
function getComprobantes($type){
   // Cache::forget($type.'_comprobantes_'.env('STORE_ID'));
    $comprobantes=Cache::get($type.'_comprobantes_'.env('STORE_ID'));
    if(!$comprobantes){
        $comprobantes=Comprobante::where('prefix',$type)->where('store_id',env('STORE_ID'))->where('status','disponible')->orderBy('number')->get();
        Cache::put($type.'_comprobantes_'.env('STORE_ID'),$comprobantes);
    }
    
    return collect($comprobantes);
}