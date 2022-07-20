<?php

namespace App\Http\Controllers;

use App\Models\Cuadre;
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
    public function index( $day=null)
    {
        if($day==null){
            $day = Carbon::parse($day)->format('Y-m-d');
        }
        $date=Carbon::parse($day)->format('Ymd');
        $place = auth()->user()->place;
        $payments = $place->payments()->where('payable_type', Invoice::class)
            ->where('payments.day', $day)
            ->join('invoices', 'invoices.id', '=', 'payments.payable_id')
            ->join('clients', 'clients.id', '=', 'payments.payer_id')
            ->join('moso_master.users', 'users.id', '=', 'payments.contable_id')
            ->orderBy('payments.created_at', 'desc')
            ->select('payments.*', 'invoices.name as name', 'invoices.number', 'clients.name as client_name')
            ->with('payer', 'payable')->get();
       
        $gastos = $place->payments()->where('payable_type', Outcome::class)
            ->join('invoices', 'invoices.id', '=', 'payments.payable_id')
            ->join('clients', 'clients.id', '=', 'payments.payer_id')
            ->join('moso_master.users', 'users.id', '=', 'payments.contable_id')
            ->orderBy('payments.created_at', 'desc')
            ->select('payments.*', 'invoices.name as name', 'invoices.number', 'clients.name as client_name')
            ->with('payer', 'payable')->get();
        $invoices = $place->invoices()->where('invoices.day', Carbon::now()->format('Y-m-d'))->get();
        $cuadre = $this->createCuadre($invoices, $payments, $day);
        $ctaCajaGeneral = $place->findCount('100-01')->balance;
        $efectivos = $place->counts()->where('code', 'like', '100%')->pluck('balance', 'name');
        $PDF = App::make('dompdf.wrapper');
        $data = [
            'payments' => $payments,
            'invoices' => $invoices,
            'gastos' => $gastos,
            'cuadre' => $cuadre,
            'ctaCajaGeneral' => $ctaCajaGeneral,
            'pdf' => $PDF,
            'efectivos' => $efectivos,
        ];
        $pdf = $PDF->loadView('pages.cuadres.pdf-cuadre', $data);
        file_put_contents('storage/cuadres/' . 'cuadre_diario_' . $date . $place->id . '.pdf', $pdf->output());
        $path = asset('storage/cuadres/' . 'cuadre_diario_' . $date . $place->id . '.pdf');
        $cuadre->pdf()->updateOrCreate(['fileable_id' => $cuadre->id], [
            'note' => 'Cuadre del ' . $date,
            'pathLetter' => $path,
            'pathThermal' => ' ',
        ]);
        return view('pages.cuadres.index', compact('cuadre'));
    }
    public function createCuadre($invoices, $payments, $day)
    {
        $place = auth()->user()->place;
        $ctaCajaGeneral=$place->cash()->balance;
        $cuadre = $place->cuadres()->updateOrCreate(['day' => $day], [
            'efectivo' =>$payments->sum('efectivo')-$payments->sum('cambio'),
            'tarjeta' =>$payments->sum('tarjeta'),
            'transferencia' => $payments->sum('trasferencia'),
            'contado' =>$payments->sum('efectivo') - $payments->sum('cambio') + $payments->sum('transferencia') + $payments->sum('tarjeta'),
            'devolucion' => 0,
            'credito' => $invoices->sum('rest'),
            'cobro' => 0,
            'egreso' => 0,
            'day' => $day,
        ]);
        $total = $cuadre->efectivo + $cuadre->tarjeta + $cuadre->transferencia;
        $cuadre->update([
            'total' => $total,
            'retirado' => ($payments->sum('efectivo') - $payments->sum('cambio')+$cuadre->inicial)-$cuadre->final,
            'final' => $ctaCajaGeneral
        ]);
        $this->openNewCuadre($ctaCajaGeneral, $day);
        return $cuadre;
    }
    public function openNewCuadre($inicial, $dateToday)
    {
        $place = auth()->user()->place;
        $day = Carbon::parse($dateToday)->addDay()->format('Y-m-d');
        $cuadre = $place->cuadres()->updateOrCreate(['day' => $day], [
            'efectivo' => 0,
            'tarjeta' => 0,
            'transferencia' => 0,
            'contado' => 0,
            'credito' => 0,
            'cobro' => 0,
            'egreso' => 0,
            'inicial' => $inicial,
            'day' => $day,
        ]);
    }
    public function show(Cuadre $cuadre)
    {
        $cuadre = $cuadre->load('pdf');
        return view('pages.cuadres.show', compact('cuadre'));
    }
}
