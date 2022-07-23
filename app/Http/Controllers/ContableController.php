<?php

namespace App\Http\Controllers;

use App\Exports\Comprobantes\ComprobanteExport;
use App\Models\CountMain;
use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ContableController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:Ver Transacciones'])->only(['general_daily', 'general_mayor', 'results']);
        $this->middleware(['permission:Ver Catálogo'])->only(['catalogue', 'view_catalogue']);
    }
    public function general_daily()
    {
        return view('pages.contables.general_daily');
    }
    public function general_mayor()
    {
        return view('pages.contables.general_mayor');
    }
    public function catalogue()
    {
        return view('pages.contables.catalogue');
    }
    public function view_catalogue()
    {
        $PDF = App::make('dompdf.wrapper');
        $counts = CountMain::with('counts')->get();
        $data = [
            'ctaControls' => $counts,

        ];
        $pdf = $PDF->loadView('pages.contables.pdf-catalogue', $data);
        $place = auth()->user()->place;
        file_put_contents('storage/cuadres/' . 'catalogo' . date('Ymd') . $place->id . '.pdf', $pdf->output());
        $path = asset('storage/cuadres/' . 'catalogo' . date('Ymd') . $place->id . '.pdf');

        return view('pages.contables.view_catalogue', ['pdf' => $path]);
    }
    public function results()
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
        $pasivo_capital = $pasivo + $capital + $utilidad_antes_impuestos;
        $date = 'Del ' . Carbon::now()->firstOfMonth()->format('d/m/Y') . ' al ' . Carbon::now()->endOfMonth()->format('d/m/Y');
        $data = get_defined_vars();
        $PDF = App::make('dompdf.wrapper');
        $pdf = $PDF->loadView('pages.contables.pdf-results', compact('data'));
        file_put_contents('storage/cuadres/' . 'result' . date('Ymd') . $place->id . '.pdf', $pdf->output());
        $path = asset('storage/cuadres/' . 'result' . date('Ymd') . $place->id . '.pdf');
        return view('pages.contables.view-results', compact('path'));
    }
    public function report_607()
    {
        $store = auth()->user()->store;
        $invoices = $store->invoices()->has('comprobante')
            ->where('type', '!=', 'B02')
            ->orWhereHas('payment', function ($query) {
                $query->where('total', '>', 250000);
            })
            ->whereHas('comprobante', function ($query) {
                $query->where('status', 'usado');
            })
            ->orderBy('invoices.id')
            ->where('invoices.status', '!=', 'waiting')
            ->with('comprobante', 'client', 'payment', 'payments')->get();



        $payments = Payment::where('payable_type', 'App\Models\Invoice')
            ->where('rest', '>=', 0)
            
            ->with('payable.payment', 'payable.comprobante',)->get();
        $efectivo = 0;
        $transferencia = 0;
        $rest = 0;
        $total = 0;
        $tax=0;
        $count=0;
        foreach ($payments as $payment) {
            if ($payment->payable->comprobante && $payment->payable->comprobante->status == 'usado' && $payment->payable->type == 'B02') {
                if ($payment->payable->payment->total <= 250000) {
                    $efectivo += $payment->efectivo>0?($payment->efectivo - $payment->cambio):0;
                    $transferencia += $payment->transferencia + $payment->tarjeta;
                    if ($payment->id == $payment->payable->payment->id) {
                        $rest += $payment->payable->rest;
                        $total += $payment->payable->payment->total;
                        $tax += $payment->payable->payment->tax;
                        $count++;
                    }
                }
            }
        }
        $efectivo=$total-($transferencia + $rest);
        $data = get_defined_vars();
        $PDF = App::make('dompdf.wrapper');
        
        $pdf = $PDF->loadView('pages.contables.pdf-607', $data);
        $content = $pdf->download()->getOriginalContent();
        $name='carnibores/reporte 607/report'.date('Ymdhis').'.pdf';
        Storage::disk('digitalocean')->put($name, $pdf->output(), 'public');
        $url= Storage::url($name);
        return view('pages.contables.report-607', compact('url'));
        return Excel::download(new ComprobanteExport, 'comprobantes.xlsx');
    }
}
