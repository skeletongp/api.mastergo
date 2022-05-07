<?php

namespace App\Http\Livewire\Invoices\Includes;

use App\Events\NewInvoice;
use App\Models\Detail;
use App\Models\Invoice;
use Illuminate\Support\Arr;

trait GenerateInvoiceTrait
{

    public function createDetails($invoice)
    {
        foreach ($this->details as $ind => $detail) {
            unset($this->details[$ind]['product_name']);
            unset($this->details[$ind]['unit_name']);
            unset($this->details[$ind]['id']);
            $detail['detailable_id'] = $invoice->id;
            $detail['detailable_type'] = Invoice::class;
            $taxes = empty($detail['taxes'])?[]:$detail['taxes'];
            $det = Detail::create(Arr::except($detail, 'taxes'));
            $det->taxes()->sync($taxes);
            $det->taxtotal = $det->taxes->sum('rate') * $det->subtotal;
            $det->save();
            $this->restStock($detail['unit_pivot_id'], $detail['cant']);
        }
    }
    public function setFromScan()
    {
        $scanned = explode('.', substr($this->scanned, 1), 4);
        $this->selProducto($scanned[0]);
        $this->form['product_id'] = $scanned[0];
        $this->form['unit_id'] = $scanned[1];
        $this->form['cant'] = $scanned[2];
        $this->form['cost'] = $scanned[3];
        $this->setProduct($this->form['product_id']);
        $this->updatedForm(13, 'unit_id');
        $this->tryAddItems();
    }
    public function sendInvoice()
    {
        if (!count($this->details)) {
            return ;
        }
        $total=array_sum(array_column($this->details, 'subtotal'));
        $user = auth()->user();
        if ($this->condition!='DE CONTADO' && $this->verifyCredit($total, $this->client['limit'])) {
            $this->emit('showAlert', 'El cliente no tiene balance suficiente', 'error');
            return false;
        }
        $invoice = $user->store->invoices()->create(
            [

                'day' => date('Y-m-d'),
                'seller_id' => $user->id,
                'condition'=>$this->condition,
                'expires_at'=>$this->vence,
                'contable_id' => $user->id,
                'place_id' => $user->place->id,
                'store_id' => $user->store->id,
                'client_id' => $this->client['id'],
                'comprobante_id' => $this->comprobante_id,
                'status' => 'waiting',
                'type' => $this->type,
            ]
        );
        $this->createPayment($invoice);
        $this->createDetails($invoice);
        event(new NewInvoice($invoice));
        $this->reset('form', 'details', 'producto', 'price', 'client','client_code','product_code','product_name');
        $this->mount();
        $this->emit('showAlert', 'Factura enviada exitosamente', 'success');
    }
    public function createPayment($invoice)
    {
        $data = [
            'ncf'=>optional($invoice->comprobante)->number,
            'amount' => array_sum(array_column($this->details, 'subtotal')),
            'discount' => array_sum(array_column($this->details, 'discount')),
            'total' =>  array_sum(array_column($this->details, 'subtotal')),
            'payed' => 0,
            'rest' =>  array_sum(array_column($this->details, 'subtotal')),
            'cambio' =>  0,
            'efectivo' => 0,
            'tarjeta' => 0,
            'tax' => 0,
            'transferencia' => 0,
        ];
       $invoice->payment()->save(setPayment($data));
    }
    public function restStock($pivotUnitId, $cant)
    {
        $user = auth()->user();
        $unit = $user->place->units()->wherePivot('id', $pivotUnitId)->first();
        $unit->pivot->stock = $unit->stock - $cant;
        $unit->pivot->save();
    }
    public function verifyCredit($amount, $credit)
    {
       return !$amount>$credit;
    }
    
}
