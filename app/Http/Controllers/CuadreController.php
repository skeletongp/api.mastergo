<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Outcome;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class CuadreController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:Ver Cuadre'])->only(['index']);
        $this->middleware(['permission:Abrir Cuadre'])->only(['createCuadre']);
    }
    public function index (Request $request){
        $place=auth()->user()->place;
        $payments=$place->payments()->with('payable','payer')->where('payable_type', Invoice::class)->where('day',date('Y-m-d'));
        $gastos=$place->payments()->with('payable','payer')->where('payable_type', Outcome::class)->where('day',date('Y-m-d'));
      
        $retirado=0;
        if ($request->has('retirado')) {
            $retirado=$request->retirado;
        }
        $pagos=$payments->get();
        $movimientos=$pagos->merge($gastos->get());
        $cuadre=$this->createCuadre($payments, $retirado);
        $ctaCajaGeneral=$place->findCount('100-01')->balance;
        $efectivos=$place->counts()->where('code','like','100%')->pluck('balance','name');
        $PDF = App::make('dompdf.wrapper');
        $data = [
            'payments'=>$pagos,
            'gastos'=>$gastos,
            'cuadre'=>$cuadre,
            'ctaCajaGeneral'=>$ctaCajaGeneral,
            'pdf'=>$PDF,
            'efectivos'=>$efectivos,
        ];
        $pdf = $PDF->loadView('pages.cuadres.pdf-cuadre', $data);
        file_put_contents('storage/cuadres/' . 'cuadre_diario_'.date('Ymd').$place->id.'.pdf', $pdf->output());
        $path = asset('storage/cuadres/' . 'cuadre_diario_'.date('Ymd').$place->id.'.pdf');
        $cuadre->pdf()->updateOrCreate(['fileable_id'=>$cuadre->id],[
            'note' => 'Cuadre del ' . date('d/m/Y'),
            'pathLetter' => $path,
            'pathThermal' => ' ',
        ]);
        return view('pages.cuadres.index', compact('cuadre'));

    }
    public function createCuadre($payments, $retirado)
    {
        $place=auth()->user()->place;
        $contado=$place->payments()->where('payable_type',Invoice::class)->with('payable','payer')->where('day',date('Y-m-d'))
        ->where('forma','!=','cobro')
        ->sum(DB::raw('efectivo + tarjeta + transferencia-cambio'));
        $credito=$place->payments()->with('payable','payer')->where('day',date('Y-m-d'))->get()->sum( function($pay){
            return $pay->payable->rest;
        });
        $cobro=$place->payments()->with('payable','payer')->where('day',date('Y-m-d'))->whereForma('cobro');
        $place=auth()->user()->place;
        $outcomes=$place->payments()->with('payable','payer')->where('payable_type', Outcome::class)->where('day',date('Y-m-d'));

        $todosEfectivos=$place->counts()->where('code','like','100%')->sum('balance');
        $ctaEfectivo=$place->counts()->whereIn('code',['100-01','100-02'])->sum('balance');
       
        $ctaCajaGeneral=$place->findCount('100-01')->balance;
        $ctaOtros=$place->counts()->whereIn('code',['100-03','100-04'])->sum('balance');
        $ctaBancos=$todosEfectivos-$ctaEfectivo-$ctaOtros;
        $devIds=$place->counts()->where('code','like','401%')->pluck('counts.id')->toArray();
        $devAmountDebit=$place->transactions()->whereIn('debitable_id',$devIds)->where('day',date('Y-m-d'))->sum('income');
        $devAmountCredit=$place->transactions()->whereIn('creditable_id',$devIds)->where('day',date('Y-m-d'))->sum('outcome');
      $debAmount=$devAmountDebit-$devAmountCredit;
      //dd($debAmount, $devAmountDebit, $devAmountCredit);
        $cuadre=$place->cuadres()->updateOrCreate(['day'=>date('Y-m-d')],[
            'efectivo'=>$ctaEfectivo,
            'tarjeta'=>$ctaOtros,
            'transferencia'=> $ctaBancos,
            'contado'=>$contado,
            'devolucion'=>$debAmount,
            'credito'=>$credito,
            'cobro'=>$cobro->sum(DB::raw('efectivo + tarjeta + transferencia-cambio')),
            'egreso'=>$outcomes->sum('payed'),
            'day'=>date('Y-m-d'),
        ]);
        $total=$cuadre->efectivo+$cuadre->tarjeta+$cuadre->transferencia;
        $cuadre->update([
            'total'=>$total,
            'retirado'=>$cuadre->inicial-$ctaCajaGeneral,
            'final'=>$ctaCajaGeneral
        ]);
        $this->openNewCuadre($ctaCajaGeneral);
        return $cuadre;
    }
    public function openNewCuadre($inicial)
    {
        $place=auth()->user()->place;
        $day=Carbon::now()->addDay()->format('Y-m-d');
        $cuadre=$place->cuadres()->updateOrCreate(['day'=>$day],[
            'efectivo'=>0,
            'tarjeta'=>0,
            'transferencia'=>0,
            'contado'=>0,
            'credito'=>0,
            'cobro'=>0,
            'egreso'=>0,
            'inicial'=>$inicial,
            'day'=>$day,
        ]);
    }
}