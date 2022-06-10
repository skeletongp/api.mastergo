<?php

namespace App\Http\Livewire\Invoices;

use App\Http\Helper\Universal;
use App\Http\Livewire\General\Authorize;
use App\Http\Livewire\Invoices\Includes\OrderConfirmTrait;
use App\Http\Livewire\Invoices\Includes\OrderContable;
use App\Models\Bank;
use App\Models\Invoice;
use Livewire\Component;
use Livewire\WithFileUploads;

class OrderConfirm extends Component
{
    use OrderContable, OrderConfirmTrait, WithFileUploads, Authorize;
    public  $form, $compAvail = true;
    public $banks, $bank, $bank_id, $reference;
    public $cheque, $photo_path;

    protected $listeners=['payInvoice', 'reload'=>'render'];

    public function mount($invoice)
    {
        $store = auth()->user()->store;
        $this->form = $invoice;
        $this->banks = $store->banks->pluck('bank_name', 'id');
        unset($invoice['payment']['id']);
        $this->form = array_merge($this->form, $invoice['payment']);
       
        if ($invoice['condition'] == 'De Contado') {
            $this->form['efectivo'] = $this->form['rest'];
        }
        $this->form['contable_id'] = auth()->user()->id;
        $this->updatedForm($this->form['efectivo'], 'efectivo');
    }
    public function render()
    {
        return view('livewire.invoices.order-confirm');
    }
    public function updatedForm($value, $key)
    {
        switch ($key) {
            case 'efectivo':
            case 'tarjeta':
            case 'transferencia':
                $this->form['payed'] = $this->form['efectivo'] +
                    $this->form['tarjeta'] + $this->form['transferencia'];
                break;

            default:
                # code...
                break;
        }
        $this->form['total'] = round(floatval($this->form['amount']) + floatval($this->form['tax']) - floatval($this->form['discount']), 2);
        $this->setPendiente();
    }

    public function validateData($invoice)
    {
        $rules = orderConfirmRules();
        if ($this->photo_path) {
            $invoice->image()->create([
                'path' => $this->photo_path
            ]);
        }
        if ($this->form['transferencia'] > 0) {
            $rules = array_merge($rules, ['bank' => 'required']);
            $rules = array_merge($rules, ['reference' => 'required']);
        }
        if ($this->form['tarjeta'] > 0) {
            $rules = array_merge($rules, ['cheque' => 'required']);
        }
        $this->bank = Bank::find($this->bank_id);
        $this->validate($rules);
    }
    public function updatedCheque()
    {
        $this->reset('photo_path');
        $this->validate([
            'cheque'=>'image|max:2048'
        ]);
        $path = cloudinary()->upload($this->cheque->getRealPath())->getSecurePath();
        $this->photo_path = $path;
    }
}
