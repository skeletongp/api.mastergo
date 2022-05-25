<?php

namespace App\Http\Livewire\Invoices\Includes;

use App\Events\NewInvoice;
use App\Models\Detail;
use App\Models\Invoice;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

trait GenerateInvoiceTrait
{
    public $invoice;

    public function createDetails($invoice)
    {
        foreach ($this->details as $ind => $detail) {
            unset($this->details[$ind]['product_name']);
            unset($this->details[$ind]['unit_name']);
            unset($this->details[$ind]['id']);
            $detail['detailable_id'] = $invoice->id;
            $detail['detailable_type'] = Invoice::class;
            $taxes = empty($detail['taxes']) ? [] : $detail['taxes'];
            if ($invoice->type == 'B00' || $invoice->type == 'B14') {
                $detail['total']=$detail['subtotal']-$detail['discount'];
            }
            $det = Detail::create(Arr::except($detail, 'taxes'));
            if ($invoice->type != 'B00' && $invoice->type != 'B14') {
                $det->taxes()->sync($taxes);
                $det->taxtotal = $det->taxes->sum('rate') * $det->subtotal;
            }
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

    public function trySendInvoice()
    {
        $condition = $this->condition != 'DE CONTADO' && array_sum(array_column($this->details, 'total')) > $this->client['limit'];

        if ($condition && !auth()->user()->hasPermissionTo('Autorizar')) {
            $this->action = 'sendInvoice';
            $this->emit('openAuthorize');
        } else {
            $this->sendInvoice();
        }
    }

    public function sendInvoice()
    {
        if (!count($this->details)) {
            return;
        }
        $total = array_sum(array_column($this->details, 'subtotal'));
        $user = auth()->user();
        $invoice = $user->store->invoices()->create(
            [

                'day' => date('Y-m-d'),
                'seller_id' => $user->id,
                'condition' => $this->condition,
                'expires_at' => $this->vence,
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
        if ($invoice->type != 'B00' && $invoice->type != 'B14') {
            $this->createInvoiceTaxes($invoice);
        }
        event(new NewInvoice($invoice));
        $this->reset('form', 'details', 'producto', 'price', 'client', 'client_code', 'product_code', 'product_name');
        $this->invoice = $invoice;
        $this->emit('openData');
        $this->mount();
    }
    public function createPayment($invoice)
    {
        $subtotal = array_sum(array_column($this->details, 'subtotal'));
        $discount = array_sum(array_column($this->details, 'discount'));
        $tax=0;
        if ($invoice->type != 'B00' && $invoice->type != 'B14') {
            $tax = array_sum(array_column($this->details, 'taxTotal'));
        }
        $total = $subtotal - $discount + $tax;

        $data = [
            'ncf' => optional($invoice->comprobante)->number,
            'amount' => $subtotal,
            'discount' => $discount,
            'total' =>  $total,
            'tax' =>  $tax,
            'payed' => 0,
            'rest' =>  $total,
            'cambio' =>  0,
            'efectivo' => 0,
            'tarjeta' => 0,
            'transferencia' => 0,
        ];
        $invoice->payment()->save(setPayment($data));
        $invoice->client->payments()->save($invoice->payment);
    }
    public function restStock($pivotUnitId, $cant)
    {
        $user = auth()->user();
        $unit = $user->place->units()->wherePivot('id', $pivotUnitId)->first();
        $unit->pivot->stock = floatval($unit->stock) - $cant;
        $unit->pivot->save();
    }
    public function verifyCredit($amount, $credit)
    {
        return !$amount > $credit;
    }
    public function createInvoiceTaxes($invoice)
    {
        $details = $invoice->details()->with('taxes')->get();
        foreach ($details as $detail) {
            foreach ($detail->taxes as $tax) {
                DB::table('invoice_taxes')->updateOrInsert(
                    [
                        'tax_id' => $tax->id,
                        'invoice_id' => $invoice->id
                    ],
                    [
                        'tax_id' => $tax->id,
                        'amount' => DB::raw('amount +' . $tax->rate * $detail->subtotal)
                    ]
                );
            }
        }
    }
}
