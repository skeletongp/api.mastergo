<?php

namespace App\Http\Livewire\Invoices;

use App\Events\NewInvoice;
use App\Http\Helper\Universal;
use App\Http\Livewire\General\Authorize;
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
    use WithPagination, ClientSectionTrait, GenerateInvoiceTrait, DetailsSectionTrait, InvoiceData, Authorize;
    public $form = [], $cant = 0, $price, $discount=0, $total, $taxTotal=0, $scanned;
    public $details = [];
    public $producto;
    public $action;
    public  $unit, $unit_id;
    protected $listeners = ['selProducto', 'tryAddItems', 'realoadClients','confirmedAddItems', 'sendInvoice'];
    protected $queryString = [ 'details','client','client_code', 'vence','condition','type'];

   
    public function mount()
    {
        $store = auth()->user()->store;
        $this->vence = Carbon::now()->addDays(30)->format('Y-m-d');
        $this->condition = 'DE CONTADO';
        $this->type = 'B02';
        $this->number = str_pad($store->invoices()->count() + 1, 7, '0', STR_PAD_LEFT);
        $this->clients = $store->clients()->orderBy('lastname')->pluck('fullname', 'code');
        $this->products = $store->products()->orderBy('name')->pluck('name', 'code');
        $this->seller = auth()->user()->fullname;
        $this->checkComprobante($this->type);
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
        $this->freshUnitId();
    }
   
   
    public function refresh()
    {
        $this->reset('form', 'details', 'producto', 'price', 'client','client_code','condition');
        $this->render();
    }
}
