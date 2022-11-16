<?php

namespace App\Http\Livewire\Contables;

use App\Models\Outcome;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Report606 extends Component
{
    public $url, $start_at, $end_at;


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
        $this->start_at=Carbon::parse($this->start_at)->format('Y-m-d 00:00:00');
        $start_at=$this->start_at;
        $this->end_at=Carbon::parse($this->end_at)->format('Y-m-d 23:59:59');
        $outcomes=Outcome::where('outcomes.store_id',$store->id)
            ->whereBetween('outcomes.created_at', [$this->start_at, $this->end_at])
            ->whereNotNull('outcomes.ncf')
            ->leftJoin('payments','payments.payable_id','=','outcomes.id')
            ->where('payments.payable_type','App\Models\Outcome')
            ->leftJoin('providers','outcomes.outcomeable_id','=','providers.id')
            ->where('outcomes.outcomeable_type','App\Models\Provider')
            ->selectRaw('outcomes.*, providers.rnc as rnc, MAX(payments.created_at) as day')
            ->groupBy('outcomes.id')
            ->orderBy('payments.id','desc')
            ->get();

        $data = get_defined_vars();
        $PDF = App::make('dompdf.wrapper');

        $pdf = $PDF->loadView('pages.contables.pdf-606', $data);
        $pdf->download();
        $name = 'files' . $store->id . '/reporte 606/report' . Carbon::parse($this->start_at)->format('Ym') . '.pdf';
        Storage::disk('digitalocean')->put($name, $pdf->output(), 'public');
        $url = Storage::url($name);
        $this->url = $url;
    }
    public function changeDate()
    {
        $this->make606();
    }
}