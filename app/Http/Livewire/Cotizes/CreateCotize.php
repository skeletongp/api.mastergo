<?php

namespace App\Http\Livewire\Cotizes;

use App\Http\Livewire\General\Authorize;
use App\Http\Livewire\Invoices\Includes\ClientSectionTrait;
use App\Http\Livewire\Invoices\Includes\DetailsSectionTrait;
use App\Http\Livewire\Invoices\Includes\InvoiceData;
use App\Models\Cotize;
use App\Models\Detail;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class CreateCotize extends Component
{
    use WithPagination, ClientSectionTrait, DetailsSectionTrait, InvoiceData, Authorize;
    public $form = [], $cant = 0, $price, $discount = 0, $total, $taxTotal = 0, $scanned;
    public $localKeys = [], $localDetail;
    public $details = [];
    public $producto;
    public $action;
    public  $unit, $unit_id;
    public $cotize;
    protected $listeners = ['selProducto', 'tryAddItems', 'realoadClients', 'confirmedAddItems', 'sendInvoice'];
    protected $queryString = ['details', 'client', 'client_code', 'vence', 'condition', 'type'];
    public function mount()
    {

        $store = auth()->user()->store;
        $place = auth()->user()->place;
        $this->vence = Carbon::now()->format('Y-m-d');
        $this->condition = 'DE CONTADO';
        $this->type = $place->preference->comprobante_type;
        $this->checkComprobante($this->type);
        $this->number = $place->id . '-' . str_pad($place->invoices()->withTrashed()->count() + 1, 7, '0', STR_PAD_LEFT);
        $this->clients = $store->clients()->orderBy('name')->pluck('name', 'code');
        $this->products = $store->products()->orderBy('name')->pluck('name', 'code');
        $this->seller = auth()->user()->fullname;
        $this->client_code = '001';
        $this->changeClient();
        $this->checkComprobante($this->type);
    }

    public function render()
    {
        return view('livewire.cotizes.create-cotize');
    }
  

    public function generateInvoice()
    {
        $this->validate([
            'client' => 'required',
            'details' => 'required|min:1',
    
        ]);
       $this->createCotize();
        $this->createDetails($this->cotize);
        $this->createPDF($this->cotize);
        return redirect()->route('cotizes.index');
    }
    public function createCotize()
    {
        $cotize = Cotize::create([
            'store_id' => auth()->user()->store->id,
            'place_id' => auth()->user()->place->id,
            'user_id' => auth()->user()->id,
            'client_id' => $this->client['id'],
            'amount' => array_sum(array_column($this->details, 'subtotal')),
            'discount' => array_sum(array_column($this->details, 'discount')),
            'total' => array_sum(array_column($this->details, 'total')),
            'day'=>Carbon::now()->format('Y-m-d'),
  
        ]);  
        $this->cotize= $cotize->load('details','client','store','place','user');
    }
    public function createDetails($cotize){

        $place=auth()->user()->place;
    
        foreach($this->details as $detail){
            $cotize->details()->create($detail);
        }  
    }
    public function createPDF($cotize){
        $data = [
            'cotize' => $cotize->load('details','user','client','place','store'),
        ];
        $PDF = App::make('dompdf.wrapper');
        $pdf = $PDF->setOptions([
            'logOutputFile' => null,
            'isHtml5ParserEnabled'=> true,
            'isRemoteEnabled'=> true
        ])->loadView('pages.cotizes.letter', $data);
        $store=$cotize->store;
        $name='files'.$store->id.'/cotizes/cotize'.$cotize->id.'.pdf';
        Storage::disk('digitalocean')->put($name, $pdf->output(), 'public');
        $url= Storage::url($name);
        $cotize->pdf()->create([
            'pathLetter'=>$url,
            'note' => 'Cotización Nº. ' . $cotize->id,
        ]);
    }
}
