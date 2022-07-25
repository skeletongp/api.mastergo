<?php

namespace App\Http\Livewire\Contables;

use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Report607 extends Component
{
    public $url, $start_at, $end_at;


    public function mount()
    {
        $this->start_at = Carbon::now()->firstOfMonth()->format('Y-m-d');
        $this->end_at = Carbon::now()->format('Y-m-d');
    }
    public function render()
    {
        $this->make607();
        return view('livewire.contables.report607');
    }
   
    public function make607()
    {
        dd($this);
        $store = auth()->user()->store;
        $invoices = $store->invoices()->has('comprobante')
            ->whereDate('created_at', '>=', $this->start_at)
            ->whereDate('created_at', '<=', $this->end_at)
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
        $tax = 0;
        $count = 0;
        foreach ($payments as $payment) {
            if ($payment->payable->comprobante && $payment->payable->comprobante->status == 'usado' && $payment->payable->type == 'B02') {
                if ($payment->payable->payment->total <= 250000) {
                    $efectivo += $payment->efectivo > 0 ? ($payment->efectivo - $payment->cambio) : 0;
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
        $efectivo = $total - ($transferencia + $rest);
        $data = get_defined_vars();
        $PDF = App::make('dompdf.wrapper');

        $pdf = $PDF->loadView('pages.contables.pdf-607', $data);
        $name = 'files' . $store->id . '/reporte 607/report' . date('Ymdhis') . '.pdf';
        Storage::disk('digitalocean')->put($name, $pdf->output(), 'public');
        $url = Storage::url($name);
        $this->url = $url;
    }
    public function changeDate(){
        $this->make607();
    }
}
