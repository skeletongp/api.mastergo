<?php

namespace App\Http\Livewire\Contables;

use App\Models\Comprobante;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Report608 extends Component
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
        $this->make608();
        return view('livewire.contables.report608');
    }
    public function make608()
    {
        $store = auth()->user()->store;
        $start_at = $this->start_at;
        $comprobantes = $this->getComprobantes($start_at);
        $data = get_defined_vars();
        $PDF = App::make('dompdf.wrapper');
        $pdf = $PDF->loadView('pages.contables.pdf-608', $data);

        $this->url = base64_encode($pdf->output());
    }
    public function getComprobantes($start_at)
    {
        $comprobantes = Comprobante::where('comprobantes.status', 'anulado')
            ->leftJoin('invoices', 'comprobantes.id', '=', 'invoices.comprobante_id')
            ->where('invoices.deleted_at', null)
            ->whereBetween(DB::raw('DATE(comprobantes.updated_at)'), [$start_at, $this->end_at])
            ->selectRaw('comprobantes.ncf as ncf, invoices.day as day, comprobantes.motivo as motivo')
            ->orderBy('comprobantes.ncf')
            ->get();
        return $comprobantes;
    }
    public function changeDate()
    {
        $this->make608();
    }
}
