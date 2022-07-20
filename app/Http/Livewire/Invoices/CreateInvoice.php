<?php

namespace App\Http\Livewire\Invoices;


use App\Http\Livewire\General\Authorize;
use App\Http\Traits\Livewire\WithPagination;

use Livewire\Component;
use App\Http\Livewire\Invoices\Includes\ClientSectionTrait;
use App\Http\Livewire\Invoices\Includes\DetailsSectionTrait;
use App\Http\Livewire\Invoices\Includes\GenerateInvoiceTrait;
use App\Http\Livewire\Invoices\Includes\InvoiceData;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use SNMP;

class CreateInvoice extends Component
{
    use WithPagination, ClientSectionTrait, GenerateInvoiceTrait, DetailsSectionTrait, InvoiceData, Authorize;
    public $form = [], $cant = 0, $price, $discount = 0, $total, $taxTotal = 0, $scanned;
    public $localKeys=[], $localDetail;
    public $details = [];
    public $producto;
    public $action;
    public  $unit, $unit_id;
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
        $this->client_code = $store->generic->code;
        $this->changeClient();
        $this->checkComprobante($this->type);
    }
    public function render()
    {
        $dataFile = file_get_contents(storage_path('app/public/local/details.json'));
        $data = json_decode($dataFile, true) ?: [];
        $this->localKeys = array_keys($data);
        return view('livewire.invoices.create-invoice');
    }
    public function checkCompAmount($store)
    {
        $comps = $store->comprobantes()->groupBy('prefix')
        ->where('status', 'disponible')
        ->select(DB::raw('type, count(*) as cant'))->get();
        $msg = '';

        foreach ($comps as $comp) {
            if ($comp->cant < 10) {
                $msg .= $comp->type . ': ' . $comp->cant . '<br>';

            }
        }
        if ($msg) {
            $this->emit('showAlert', 'Comprobantes pronto a agotarse <br>' . $msg, 'warning', 1000);

        }
    }
    public function updatedProductCode()
    {
        $this->setProduct($this->product_code);
        $this->invoice = null;
    }



    public function refresh()
    {
        $this->reset('form', 'details', 'producto', 'price', 'client', 'client_code', 'condition');
        $this->render();
    }

    public function storageDetails()
    {
        $dataFile = file_get_contents(storage_path('app/public/local/details.json'));
        $data = json_decode($dataFile, true) ?: [];
        if ($this->client) {
            $detail = $this->details;
            $client = $this->client;
            $name=$this->name?:$client['name'];
            $data[$client['code'].' '.rtrim($name)] = $detail;
            file_put_contents(storage_path('app/public/local/details.json'), json_encode($data));
        }
        $dataFile = file_get_contents(storage_path('app/public/local/details.json'));
        $data = json_decode($dataFile, true) ?: [];
        $this->localKeys = array_keys($data);
        $this->reset('details','name','localDetail');
    }
    public function updatedLocalDetail($value){
        $dataFile = file_get_contents(storage_path('app/public/local/details.json'));
        $data = json_decode($dataFile, true) ?: [];
       if($value && array_key_exists($value, $data)){
        $this->details=$data[$value];
        $this->client_code=substr($value,0,strpos($value,' '));
        $this->name=substr($value,strpos($value,' ')+1);
        $this->updatedClientCode();
       } else{
        $this->reset('details');
        $this->client_code='001';
        $this->name='';
        $this->updatedClientCode();
       }
    }
    public function deleteLocal(){
        $dataFile = file_get_contents(storage_path('app/public/local/details.json'));
        $data = json_decode($dataFile, true) ?: [];
        if($this->localDetail){
            unset($data[$this->localDetail]);
            file_put_contents(storage_path('app/public/local/details.json'), json_encode($data));
            $this->localDetail=null;
        }
        $dataFile = file_get_contents(storage_path('app/public/local/details.json'));
        $data = json_decode($dataFile, true) ?: [];
        $this->localKeys = array_keys($data);
        $this->reset('details');
        $this->client_code='001';
        $this->name='';
        $this->updatedClientCode();
    }
}
