<?php

namespace App\Http\Livewire\Contables;

use App\Models\Comprobante;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Report607 extends Component
{
    public $url, $start_at, $end_at;

    protected $queryString = ['start_at', 'end_at'];
    public function mount()
    {
        if (!$this->start_at) {
            $this->start_at = Carbon::now()->firstOfMonth()->format('Y-m-d');
            $this->end_at = Carbon::now()->format('Y-m-d');
        }
    }
    public function render()
    {
        $this->make607();
        return view('livewire.contables.report607');
    }

    public function make607()
    {
        $store = auth()->user()->store;
        $start_at = $this->start_at;


        $comprobantes = Comprobante::where('comprobantes.status', 'usado')
            ->leftJoin('invoices', 'comprobantes.id', '=', 'invoices.comprobante_id')
            ->whereBetween('invoices.day', [$start_at, $this->end_at])
            ->leftJoin('payments', 'invoices.id', '=', 'payments.payable_id')
            ->where('payments.payable_type', 'App\Models\Invoice')
            ->leftJoin('clients', 'comprobantes.client_id', '=', 'clients.id')
            ->selectRaw('clients.rnc as rnc, invoices.rnc as invRnc ,comprobantes.ncf as ncf, invoices.day as day, 
                        sum(payments.payed-payments.cambio)+invoices.rest as amount,   
                        sum(payments.tax) as tax, sum(payments.efectivo-payments.cambio) as efectivo,
                        sum(payments.transferencia+payments.tarjeta) as transferencia, invoices.rest
                        as rest')
            ->where(function ($query) {
                $query->where('comprobantes.prefix', '!=', 'B02')
                    ->orWhere('payments.amount', '>', 250000);
            })
            ->orderBy('payments.id')
            ->groupBy('comprobantes.id')
            ->get();

        $resumen = Comprobante::where('comprobantes.status', 'usado')
            ->leftJoin('invoices', 'comprobantes.id', '=', 'invoices.comprobante_id')
            ->leftJoin('payments', 'invoices.id', '=', 'payments.payable_id')
            ->whereBetween('invoices.day', [$start_at, $this->end_at])
            ->where('payments.payable_type', 'App\Models\Invoice')
            ->selectRaw('sum(payments.tax) as tax, sum(payments.payed-payments.cambio)+invoices.rest 
                as amount, sum(payments.tax) as tax, sum(payments.efectivo-payments.cambio) as efectivo,
                sum(payments.transferencia+payments.tarjeta) as transferencia, invoices.rest
                as rest, count(comprobantes.id) as count')
            ->where('comprobantes.prefix', 'B02')
            ->where('payments.amount', '<=', 250000)
            ->orderBy('payments.id')
            ->groupBy('comprobantes.store_id')
            ->first();
        $data = get_defined_vars();
        $PDF = App::make('dompdf.wrapper');

        $pdf = $PDF->loadView('pages.contables.pdf-607', $data);
        $name = 'files' . $store->id . '/reporte 607/report' . Carbon::parse($this->start_at)->format('Ym') . '.pdf';
        Storage::disk('digitalocean')->put($name, $pdf->output(), 'public');
        $url = Storage::url($name);
        $this->url = $url;
    }
    public function changeDate()
    {
        $this->make607();
    }
}
