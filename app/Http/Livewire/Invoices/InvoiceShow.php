<?php

namespace App\Http\Livewire\Invoices;

use App\Http\Livewire\General\Authorize;
use App\Http\Livewire\Invoices\ShowIncludes\Showclient;
use App\Http\Livewire\Invoices\ShowIncludes\ShowPayments;
use App\Http\Livewire\Invoices\ShowIncludes\ShowProducts;
use App\Http\Livewire\Invoices\ShowIncludes\ShowUsers;
use App\Models\Invoice;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;

class InvoiceShow extends Component
{
    use Showclient, Authorize, ShowProducts, ShowUsers, ShowPayments, WithFileUploads;

    public Invoice $invoice;
    public $includeName = "showclient";
    public $includeTitle = 'Cliente';
    public $action;
    public $lastPayment;

    protected $rules = [
        'client' => 'required',
        'seller' => 'required',
        'contable' => 'required',
        'payment' => 'required',
        'det'=>'required',
        'prods.*.code'=>'required',
    ];
    protected $listeners = ['updateInvoiceClient', 'reloadEdit'=>'render'];
    protected $queryString=['includeName','includeTitle'];
    public function mount()
    {
        $store = auth()->user()->store;
        $this->det=$this->invoice->details;
        $this->clients = $store->clients()->orderBy('name')->pluck('fullname', 'code');
        $this->client = $this->invoice->client->load('payments')->toArray();
        $inv=$this->invoice->client->invoices()->where('id', '<', $this->invoice->id)->where('rest','>',0)->first();
        if($inv){
            $this->cobrable=false;
        }
        $this->seller = $this->invoice->seller->toArray();
        $this->contable = $this->invoice->contable->toArray();
        $this->banks = auth()->user()->store->banks->pluck('bank_name', 'id');
        $this->client['balance'] = '$' . formatNumber($this->client['limit']);
        $this->client['gasto'] = '$' . formatNumber(array_sum(array_column($this->client['payments'], 'payed')));
        $this->client_code = $this->client['code'];
        $this->payment['efectivo']=0;
        $this->payment['tarjeta']=0;
        $this->payment['transferencia']=0;
        $this->lastPayment=$this->invoice->payments()->orderBY('id','desc')->first();
    }
    public function render()
    {
        return view('livewire.invoices.invoice-show');
    }
    public function setIncludeElement($name, $title)
    {
        $this->includeName = $name;
        $this->includeTitle = $title;
        $this->render();
    }
}
