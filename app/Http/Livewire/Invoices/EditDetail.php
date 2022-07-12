<?php

namespace App\Http\Livewire\Invoices;

use App\Http\Livewire\General\Authorize;
use App\Jobs\CreatePDFJob;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class EditDetail extends Component
{

    use Authorize;
    public $place;
    public $detail, $product, $unit;
    public $prevUnitId, $prevCant, $prevTaxes, $prevRest, $prevInvTax, $prevPrice;
    public $products, $units;
    public $action = 'updateDetail';

    protected $rules = [
        'detail' => 'required',
        'detail.cant' => 'required',
        'detail.price' => 'required',
        'unit' => 'required',
        'product' => 'required',
        'product.id' => 'required',
        'product.code' => 'required',
        'product.name' => 'required',
        'unit.name' => 'required',
        'unit.pivot.id' => 'required',
    ];

    protected $listeners = ['updateDetail'];
    public function mount()
    {
        $this->place=auth()->user()->place;
        $this->product = $this->detail->product->load('units');
        $this->products = $this->place->products()->pluck('name', 'code');
        $this->units = $this->product->units()->pluck('name', 'product_place_units.id');
        $this->unit = $this->detail->unit;
        $this->prevCant = $this->detail->cant;
        $this->prevPrice = $this->detail->price;
        $prevUnit = $this->product->units()->where('units.id', $this->detail->unit_id)->first();
        $this->prevTaxes = $this->product->taxes()->pluck('taxes.id')->toArray();
        $this->prevUnitId = $prevUnit->pivot->id;
        $this->prevInvTax = $this->detail->detailable->payment->tax;
        $this->unit = $prevUnit;
    }
    public function render()
    {
        $this->detail['cant'] = formatNumber($this->detail['cant']);
        return view('livewire.invoices.edit-detail');
    }
    public function updatedProductCode()
    {
        $this->changeProduct($this->product['code']);
    }
    public function updatedUnitPivotId()
    {
        $unit = $this->place->units()->where('product_place_units.id', $this->unit->pivot['id'])->first();
        if ($unit) {
            $this->unit = $unit;
        }
    }
    public function changeProduct($code)
    {
        $code = str_pad($code, 3, '0', STR_PAD_LEFT);
        $product = Product::whereCode($code)->first();
        $this->units = $product->units()->pluck('name', 'product_place_units.id');
        if ($product) {
            $this->product = $product;
        }
    }

    public function tryUpdateDetail()
    {
        $this->updateDetail();
    }

    public function updateDetail()
    {

        $this->updateUnit();
        $this->detail->product_id = $this->product->id;
        $this->updatePrice();
        $invoice = $this->detail->detailable->load('details', 'payment', 'client');
        $this->updatePayment($invoice);
        $this->updateTransaction($invoice);
        $this->render();
        dispatch(new CreatePDFJob($invoice))->onConnection('database');
        $this->setTaxes($invoice);
        $this->emit('showAlert', 'Detalle actualizado', 'success');
        $this->emitUp('reloadEdit');
    }
    public function updatePrice()
    {
        $unit = $this->place->units()->wherePivot('id', $this->unit->pivot['id'])->first();
        $price=$this->detail->price;
        $subtotal = $price * $this->detail->cant;
        $taxTotal = $subtotal * $this->product->taxes->sum('rate');
        $total = $subtotal + $taxTotal;
        $this->detail->unit_id = $unit->id;
        $this->detail->subtotal = $subtotal;
        $this->detail->taxtotal = $taxTotal;
        $this->detail->total = $total;
        $this->detail->save();
        $this->detail->taxes()->detach($this->prevTaxes);
        $this->detail->taxes()->attach($this->product->taxes()->pluck('taxes.id')->toArray());
    }
    public function updateUnit()
    {
        $prevUnit = $this->place->units()->wherePivot('id', $this->prevUnitId)->first();
        $prevUnit->pivot->stock = $prevUnit->pivot->stock + $this->prevCant;
        $prevUnit->pivot->save();
        $unit = $this->place->units()->wherePivot('id', $this->unit->pivot['id'])->first();
        $unit->pivot->stock = $unit->pivot->stock - $this->detail->cant;
        $unit->pivot->save();
        return $unit;
    }
    public function updatePayment($invoice)
    {
        $payment = $invoice->payment;
        $this->prevRest = $payment->rest;
        $payment->amount = $invoice->details->sum('subtotal');
        $payment->discount = $invoice->details->sum('discount');
        $payment->tax = $invoice->details->sum('taxtotal');
        $payment->total = $invoice->details->sum('total');
        $payment->rest = $payment->total - $payment->payed;
        $payment->save();
        $this->updateClientLimit($this->prevRest, $invoice->client, $payment);
    }
    public function updateClientLimit($prevRest, $client, $payment)
    {
        $client->limit = $client->limit + $prevRest;
        $client->save();
        $client->limit = $client->limit - $payment->rest;
        $client->save();
    }
    public function updateTransaction($invoice)
    {
        $rest = $invoice->payment->rest;
        $tax = $invoice->payment->tax;
        $diffRest = $rest-$this->prevRest;
        $diffTax =  $tax-$this->prevInvTax;
        
        if ($this->prevPrice != $this->detail->price) {
            $desc_dev_ventas = $this->place->findCount('401-03');
        } else {
            $desc_dev_ventas = $this->place->findCount('401-01');
        }
        if ($invoice->payment->payed >= $diffTax) {
            $creditable2 = $this->place->cash();
        } else {
            $creditable2 = $invoice->client->contable()->first();
        }

        $debitable2 = $this->place->findCount('202-01');
          /* Ajuste Impuesto */
          if ($diffTax < 0) {
            setTransaction('Ajuste impuestos Fct. ' . $invoice->number, $invoice->payment->ncf ?: $invoice->number, abs($diffTax), $debitable2, $creditable2);
        } else {
            setTransaction('Ajuste impuestos Fct. ' . $invoice->number, $invoice->payment->ncf ?: $invoice->number, $diffTax, $creditable2, $debitable2);
        }
        /* Ajuste Detalle */
        if ($diffRest > 0) {
            setTransaction('Ajuste detalle Fct. ' . $invoice->number, $invoice->payment->ncf ?: $invoice->number, $diffRest, $invoice->client->contable()->first(), $desc_dev_ventas);
        } else {
            setTransaction('Ajuste detalle Fct. ' . $invoice->number, $invoice->payment->ncf ?: $invoice->number, abs($diffRest), $desc_dev_ventas, $invoice->client->contable()->first());
        }
      
    }
    public function setTaxes($invoice)
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
                        'amount' => DB::raw($tax->rate * $detail->subtotal)
                    ]
                );
            }
        }
    }
}
