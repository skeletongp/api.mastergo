<?php

namespace App\Http\Livewire\Invoices;

use App\Events\NewInvoice;
use App\Http\Helper\Universal;
use App\Http\Traits\Livewire\WithPagination;
use App\Models\Client;
use App\Models\Detail;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Unit;
use PDF;
use Illuminate\Support\Arr;
use Livewire\Component;
use App\Http\Livewire\Invoices\Includes\ClientSectionTrait;
use App\Http\Livewire\Invoices\Includes\DetailsSectionTrait;
use App\Http\Livewire\Invoices\Includes\GenerateInvoiceTrait;
use Carbon\Carbon;

class CreateInvoice extends Component
{
    use WithPagination, ClientSectionTrait, GenerateInvoiceTrait, DetailsSectionTrait;
    public $form = [], $cant = 0, $price, $discount=0, $total, $totalTax=0, $scanned;
    public $client, $client_code, $clients;
    public $product, $product_code, $products;
    public $number, $condition = "DE CONTADO", $type, $vence, $seller;
    public $details = [];

    public $producto;
    public  $unit, $unit_id;
    protected $listeners = ['selProducto', 'addItems', 'realoadClients'];

    protected $queryString = [ 'details','client','client_code'];

    function rules()
    {
        return invoiceCreateRules();
    }
    public function mount()
    {
        $store = auth()->user()->store;
        $this->vence = Carbon::now()->addDays(30)->format('Y-m-d');
        $this->clients = $store->clients()->orderBy('lastname')->pluck('fullname', 'code');
        $this->number = str_pad($store->invoices()->count() + 1, 7, '0', STR_PAD_LEFT);
        $this->seller = auth()->user()->fullname;
    }
    public function render()
    {
        return view('livewire.invoices.create-invoice');
    }
    public function updatedProductCode()
    {
        $this->setProduct($this->product_code);
    }
    public function updated()
    {
        $this->updatedUnitId();
    }
   
    public function updatedClientCode()
    {
        $this->changeClient();
    }
}
