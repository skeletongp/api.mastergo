<?php

namespace App\Http\Livewire\Contables;

use App\Models\Credit;
use App\Models\Outcome;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Report606 extends Component
{
    public $url, $start_at, $end_at;

    protected $queryString = ['start_at', 'end_at'];
    public function mount()
    {
        $this->start_at = Carbon::now()->firstOfMonth()->format('Y-m-d');
        $this->end_at = Carbon::now()->format('Y-m-d');
    }
    public function render()
    {
        $this->make606();
        return view('livewire.contables.report606');
    }

    public function make606()
    {
        $store = auth()->user()->store;
        $start_at = Carbon::parse($this->start_at)->format('Y-m-d 00:00:00');
        $end_at = Carbon::parse($this->end_at)->format('Y-m-d 23:59:59');

        $outcomes = Outcome::where('outcomes.store_id', $store->id)
            ->whereBetween('outcomes.created_at', [$start_at, $end_at])
            ->whereNotNull('outcomes.ncf')
            ->leftJoin('payments', 'payments.payable_id', '=', 'outcomes.id')
            ->where('payments.payable_type', 'App\Models\Outcome')
            ->leftJoin('providers', 'outcomes.outcomeable_id', '=', 'providers.id')
            ->where('outcomes.outcomeable_type', 'App\Models\Provider')
            ->selectRaw('outcomes.*, providers.rnc as rnc, MAX(payments.created_at) as day, SUM(payments.efectivo-payments.cambio) as efectivo,
            SUM(payments.transferencia) as transferencia')
            ->groupBy('outcomes.id')
            ->orderBy('outcomes.created_at', 'asc')
            ->get();
        $totals = [
            'products' => $outcomes->sum('products'),
            'services' => $outcomes->sum('services'),
            'total' => $outcomes->sum('products') + $outcomes->sum('services'),
            'itbis' => $outcomes->sum('itbis'),
        ];
        $creditnotes = $this->getCreditNotes($start_at, $end_at);
        $data = get_defined_vars();
        $PDF = App::make('dompdf.wrapper');

        $pdf = $PDF->loadView('pages.contables.pdf-606', $data);
        $pdf->download();
        $name = 'files' . $store->id . '/reporte 606/report' . Carbon::parse($this->start_at)->format('Ym') . '.pdf';
        /*   Storage::disk('digitalocean')->put($name, $pdf->output(), 'public');
        $url = Storage::url($name); */
        $this->url =  base64_encode($pdf->output());;
    }
    public function getCreditNotes($start_at, $end_at)
    {
        $creditnotes =
            Credit::whereBetween('modified_at', [$start_at, $end_at])
            ->where('creditable_type', Outcome::class)
            ->leftJoin('outcomes', 'credits.creditable_id', '=', 'outcomes.id')
            ->leftjoin('providers', 'outcomes.outcomeable_id', '=', 'providers.id')
            ->selectRaw(
                'providers.rnc as rnc,  credits.modified_ncf as outNcf , credits.ncf as ncf,
           outcomes.created_at as outDat, credits.modified_at as day, credits.amount as amount,
           credits.itbis as tax'
            )
            ->groupBy('credits.id')
            ->get();
        return $creditnotes;
    }
    public function changeDate()
    {
        $this->make606();
    }
}
