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
    public function index (Request $request){
        $place=auth()->user()->place;
        $payments=$place->payments()->with('payable','payer')->where('payable_type',Invoice::class)->where('day',date('Y-m-d'));
        $retirado=0;
        if ($request->has('retirado')) {
            $retirado=$request->retirado;
        }
        $pagos=$payments->get();
        $cuadre=$this->createCuadre($payments, $retirado);
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
        $outcomes=$place->payments()->with('payable','payer')->where('payable_type',Outcome::class)->where('day',date('Y-m-d'));
        $cuadre=$place->cuadres()->updateOrCreate(['day'=>date('Y-m-d')],[
            'efectivo'=>$payments->sum(DB::raw('efectivo-cambio')),
            'tarjeta'=>$payments->sum('tarjeta'),
            'transferencia'=>$payments->sum('transferencia'),
            'contado'=>$contado,
            'credito'=>$credito,
            'cobro'=>$cobro->sum(DB::raw('efectivo + tarjeta + transferencia-cambio')),
            'egreso'=>$outcomes->sum('payed'),
            'day'=>date('Y-m-d'),
        ]);
        $total=$cuadre->efectivo+$cuadre->tarjeta+$cuadre->transferencia-$cuadre->egreso;
        $cuadre->update([
            'total'=>$total,
            'retirado'=>$cuadre->retirado+$retirado,
            'final'=>$total-($cuadre->retirado+$retirado)
        ]);
        $this->openNewCuadre($cuadre->final);
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
