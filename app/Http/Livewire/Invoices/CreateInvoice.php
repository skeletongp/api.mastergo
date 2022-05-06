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
use App\Http\Livewire\Invoices\Includes\InvoiceData;
use Carbon\Carbon;

class CreateInvoice extends Component
{
    use WithPagination, ClientSectionTrait, GenerateInvoiceTrait, DetailsSectionTrait, InvoiceData;
    public $form = [], $cant = 0, $price, $discount=0, $total, $taxTotal=0, $scanned;
    public $client, $client_code, $clients;
    public $details = [];

    public $producto;
    public  $unit, $unit_id;
    protected $listeners = ['selProducto', 'addItems', 'realoadClients'];

    protected $queryString = [ 'details','client','client_code', 'vence','condition','type'];

   
    public function mount()
    {
        $store = auth()->user()->store;
        $this->vence = Carbon::now()->addDays(30)->format('Y-m-d');
        $this->condition = 'DE CONTADO';
        $this->number = str_pad($store->invoices()->count() + 1, 7, '0', STR_PAD_LEFT);
        $this->clients = $store->clients()->orderBy('lastname')->pluck('fullname', 'code');
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
    public function refresh()
    {
        $this->reset('form', 'details', 'producto', 'price', 'client','client_code','condition');
        $this->render();
    }
}
