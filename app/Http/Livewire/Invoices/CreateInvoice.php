<?php

namespace App\Http\Livewire\Invoices;


use App\Http\Traits\Livewire\WithPagination;

use Livewire\Component;
use App\Http\Livewire\Invoices\Includes\ClientSectionTrait;
use App\Http\Livewire\Invoices\Includes\DetailsSectionTrait;
use App\Http\Livewire\Invoices\Includes\GenerateInvoiceTrait;
use App\Http\Livewire\Invoices\Includes\InvoiceData;
use App\Http\Traits\Livewire\Confirm;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CreateInvoice extends Component
{
    use WithPagination, ClientSectionTrait, GenerateInvoiceTrait, DetailsSectionTrait, InvoiceData, Confirm;
    public $form = [], $cant = 0, $price, $discount = 0, $total, $taxTotal = 0, $scanned;
    public $localKeys=[], $localDetail;
    public $details = [], $banks=[];
    public $producto;
    public $action;
    public $instant=false;
    public  $unit, $unit_id;
    protected $listeners = ['selProducto', 'validateAuthorization', 'realoadClients', 'confirmedAddItems', 'sendInvoice'];
    protected $queryString = ['details', 'client', 'client_code', 'vence', 'condition', 'type'];


    public function mount()
    {
        
        $store = getStore();
        $place = getPlace();
        $this->banks = getBanks();
        $this->vence = Carbon::now()->format('Y-m-d');
        $this->condition = 'DE CONTADO';
        $this->type = getPreference($place->id)->comprobante_type;
        $this->number = $place->id . '-' . str_pad(getPlaceInvoicesWithTrashed($place->id)->count() + 1, 7, '0', STR_PAD_LEFT);
        $this->clients = clientWithCode($store->id);
        $this->products = getProductsWithCode();
        $this->seller = auth()->user()->fullname;
        $this->client_code = '0001';
        $this->changeClient();
        $this->checkComprobante($this->type);
    }
    public function render()
    {
     /*    $dataFile = file_get_contents(storage_path('app/public/local/details.json'));
        $data = json_decode($dataFile, true) ?: [];
        $this->localKeys = array_keys($data); */
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
            $this->emit('showAlert', 'Comprobantes pronto a agotarse <br>' . $msg, 'warning', 3000);

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
            $data[$client['code'].'-'.preg_replace('/\s+/', '', $name)] = $detail;
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
        $this->render();
    }
}
