<?php

namespace App\Http\Livewire\Contables;

use App\Models\Comprobante;
use App\Models\Credit;
use App\Models\Creditnote;
use App\Models\Invoice;
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
        $start_at=Carbon::parse($this->start_at)->format('Y-m-d 00:00:00');
        $end_at=Carbon::parse($this->end_at)->format('Y-m-d 23:59:59');


        $comprobantes = $this->getComprobantes($start_at, $end_at);

        $resumen = $this->getResumen($start_at, $end_at);

        $props=$this->getOtherData($resumen, $comprobantes);

        $creditnotes = $this->getCreditNotes($start_at, $end_at);
        $data = get_defined_vars();
        $PDF = App::make('dompdf.wrapper');

        $pdf = $PDF->loadView('pages.contables.pdf-607', $data);
        $name = 'files' . $store->id . '/reporte 607/report' . Carbon::parse($this->start_at)->format('Ym') . '.pdf';

        /* Storage::disk('digitalocean')->put($name, $pdf->output(), 'public');
        $url = Storage::url($name); */
        $this->url = base64_encode($pdf->output());
    }
    public function changeDate()
    {
        $this->make607();
    }
    public function getComprobantes($start_at, $end_at)
    {
        $comprobantes = Comprobante::where('comprobantes.status', 'usado')
            ->where('invoices.deleted_at', null)
            ->leftJoin('invoices', 'comprobantes.id', '=', 'invoices.comprobante_id')
            ->whereBetween('invoices.day', [$start_at, $end_at])
            ->leftJoin('payments', 'invoices.id', '=', 'payments.payable_id')
            ->where('payments.payable_type', 'App\Models\Invoice')
            ->leftJoin('clients', 'invoices.client_id', '=', 'clients.id')
            ->selectRaw('clients.rnc as rnc, invoices.rnc as invRnc,comprobantes.ncf as ncf, invoices.day as day,
           if(invoices.status!="anulada",payments.total,50) as amount,
           payments.tax as tax, if(invoices.status!="anulada",payments.efectivo-payments.cambio,50) as efectivo,
          if(invoices.status!="anulada", payments.transferencia+payments.tarjeta,0) as transferencia, payments.rest
           as rest, invoices.number as number')
            ->where(function ($query) {
                $query->where('comprobantes.prefix', '!=', 'B02')
                    ->orWhere('payments.amount', '>', 250000);
            })
            ->where('comprobantes.prefix', '!=', 'B14')
            ->where('invoices.status','cerrada')
            ->orderBy('payments.id')
            ->groupBy('comprobantes.id')
            ->get();
        return $comprobantes;
    }
    public function getResumen($start_at, $end_at)
    {
        $resumen = Comprobante::where('comprobantes.status', 'usado')
            ->where('invoices.deleted_at', null)
            ->leftJoin('invoices', 'comprobantes.id', '=', 'invoices.comprobante_id')
            ->whereBetween('invoices.day', [$start_at, $end_at])
            ->leftJoin('payments', 'invoices.id', '=', 'payments.payable_id')
            ->where('payments.payable_type', 'App\Models\Invoice')
            ->leftJoin('clients', 'invoices.client_id', '=', 'clients.id')
            ->selectRaw('if(invoices.status!="anulada",payments.payed+payments.rest-payments.cambio,50) as amount,
            payments.tax as tax, if(invoices.status!="anulada",payments.efectivo-payments.cambio,50) as efectivo,
           if(invoices.status!="anulada", payments.transferencia+payments.tarjeta,0) as transferencia,
           if(invoices.status!="anulada",payments.rest,50) as rest, if(invoices.status!="anulada",payments.cambio,50) as cambio')
            ->where('comprobantes.prefix', '=', 'B02')
            ->where('payments.amount', '<=', 250000)
            ->where('invoices.status','cerrada')
            ->orderBy('payments.id')
            ->groupBy('comprobantes.id')
            ->get();


        return $resumen;
    }
    public function getCreditNotes($start_at, $end_at)
    {
        $creditnotes =
            Credit::whereBetween('modified_at', [$start_at, $end_at])
            ->where('creditable_type', Invoice::class)
            ->leftJoin('invoices', 'credits.creditable_id', '=', 'invoices.id')
            ->leftjoin('clients', 'invoices.client_id', '=', 'clients.id')
            ->selectRaw(
                'clients.rnc as rnc, invoices.rnc as invRnc, credits.modified_ncf as invNcf ,credits.ncf as ncf,
           invoices.day as invDay, credits.modified_at as day, credits.amount as amount,
           credits.itbis as tax'
            )
            ->where('invoices.status','cerrada')
            ->groupBy('credits.id')
            ->get();
        return $creditnotes;
    }
    public function getOtherData($resumen, $comprobante){
        $resTax=$resumen->sum('tax');
        $resTotal=$resumen->sum('amount');
        $resProp=$resTax/(18/100);

        $compTax=$comprobante->sum('tax');
        $compTotal=$comprobante->sum('amount');
        $compProp=$compTax/(18/100);

        $total=$compTotal+$resTotal;
        $propTotal=$compProp+$resProp;
        return [
            'compProp'=>$compProp,
            'resProp'=>$resProp,
            'total'=>$total,
            'propTotal'=>$propTotal,
        ];
    }
}
