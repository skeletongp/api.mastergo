<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class CuadreController extends Controller
{
    public function index (){
        $place=auth()->user()->place;
        $payments=$place->payments()->with('payable','payer')->where('day',date('Y-m-d'));
      
        $pagos=$payments->get();
        $cuadre=$this->createCuadre($payments);
        $PDF = App::make('dompdf.wrapper');
        $data = [
            'payments'=>$pagos,
            'cuadre'=>$cuadre,
            'pdf'=>$PDF
        ];
        $pdf = $PDF->loadView('pages.cuadres.pdf-cuadre', $data);
        file_put_contents('storage/cuadres/' . 'cuadre_diario_'.date('YmdHi'). '.pdf', $pdf->output());
        $path = asset('storage/cuadres/' . 'cuadre_diario_'.date('YmdHi'). '.pdf');
        $cuadre->pdf()->updateOrCreate(['fileable_id'=>$cuadre->id],[
            'note' => 'Cuadre del ' . date('d/m/Y'),
            'pathLetter' => $path,
            'pathThermal' => ' ',
        ]);
        return view('pages.cuadres.index', compact('cuadre'));

    }
    public function createCuadre($payments)
    {
        $place=auth()->user()->place;
        $contado=$place->payments()->with('payable','payer')->where('day',date('Y-m-d'))
        ->where('forma','!=','cobro')
        ->sum(DB::raw('efectivo + tarjeta + transferencia-cambio'));
        $credito=$place->payments()->with('payable','payer')->where('day',date('Y-m-d'))->get()->sum( function($pay){
            return $pay->payable->rest;
        });
        $cobro=$place->payments()->with('payable','payer')->where('day',date('Y-m-d'))->whereForma('cobro');
        $place=auth()->user()->place;
        $outcomes=$place->outcomes()->where('created_at','like',date('Y-m-d').'%');
        $cuadre=$place->cuadres()->updateOrCreate(['day'=>date('Y-m-d')],[
            'efectivo'=>$payments->sum(DB::raw('efectivo-cambio')),
            'tarjeta'=>$payments->sum('tarjeta'),
            'transferencia'=>$payments->sum('transferencia'),
            'contado'=>$contado,
            'credito'=>$credito,
            'cobro'=>$cobro->sum(DB::raw('efectivo + tarjeta + transferencia-cambio')),
            'egreso'=>$outcomes->sum('amount'),
            'day'=>date('Y-m-d'),
        ]);
        $cuadre->update([
            'total'=>$cuadre->efectivo+$cuadre->tarjeta+$cuadre->transferencia-$cuadre->egreso
        ]);
        return $cuadre;
    }
}
