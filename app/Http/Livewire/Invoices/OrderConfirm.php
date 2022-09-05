<?php

namespace App\Http\Livewire\Invoices;

use App\Http\Helper\Universal;
use App\Http\Livewire\Invoices\Includes\OrderConfirmTrait;
use App\Http\Livewire\Invoices\Includes\OrderContable;
use App\Http\Traits\Livewire\Confirm;
use App\Models\Bank;
use App\Models\Invoice;
use Livewire\Component;
use Livewire\WithFileUploads;

class OrderConfirm extends Component
{
    use OrderContable, OrderConfirmTrait, WithFileUploads, Confirm;
    public  $form=[], $compAvail = true, $cobrable = true, $copyCant = 1;
    public $banks, $bank, $bank_id, $reference;
    protected $listeners = ['payInvoice', 'validateAuthorization', 'reload' => 'render','modalOpened'];
    public $invoice_id;
    public $instant=false;
    
    public function mount($invoice_id){
        $this->form['id'] = $invoice_id;
    }
    
    public function updatedCopyCant()
    {
        $this->emit('changeCant', $this->copyCant);
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
                $this->form['payed'] = floatVal($this->form['efectivo']) +
                    floatVal($this->form['tarjeta']) + floatVal($this->form['transferencia']);
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

        if (array_key_exists('transferencia',$this->form) && $this->form['transferencia'] > 0) {
            $rules = array_merge($rules, ['bank' => 'required']);
            $rules = array_merge($rules, ['reference' => 'required']);
        }

        $this->bank = Bank::find($this->bank_id);
        $this->validate($rules);
    }
    public function modalOpened()
    {
        $this->form = Invoice::find($this->invoice_id)
        ->load('seller',  'client', 'details.product.units', 'details.taxes', 'details.unit', 'payment.pdf', 'store.image', 'comprobante', 'pdf', 'place.preference')->toArray();
        $payment=$this->form['payment'];
        unset($payment['id']);
        $this->form['name']=$this->form['name']?:$this->form['client']['name'];
        unset($this->form['payment']);
        $this->form = array_merge($this->form, $payment);
       /*   if ($invoice['client']['debt']>0 || $invoice['condition']!='De Contado') {
           $this->cobrable=false;
        } */
        $this->form['contable_id'] = auth()->user()->id;
        $this->updatedForm($this->form['efectivo'], 'efectivo');
    }
}
