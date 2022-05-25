<?php

namespace App\Http\Livewire\Invoices;

use App\Http\Livewire\General\Authorize;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class EditDetail extends Component
{

    use Authorize;

    public $detail, $product, $unit;
    public $prevUnitId, $prevCant, $prevTaxes, $prevRest, $prevInvTax;
    public $products, $units;
    public $action = 'updateDetail';

    protected $rules = [
        'detail' => 'required',
        'detail.cant' => 'required',
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
        $this->product = $this->detail->product->load('units');
        $this->products = auth()->user()->place->products->pluck('name', 'code');
        $this->units = $this->product->units->pluck('name', 'pivot.id');
        $this->unit = $this->detail->unit;
        $this->prevCant = $this->detail->cant;
        $prevUnit = $this->product->units()->where('units.id', $this->detail->unit_id)->first();
        $this->prevTaxes = $this->product->taxes->pluck('id')->toArray();
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
        $unit = auth()->user()->place->units()->where('product_place_units.id', $this->unit->pivot['id'])->first();
        if ($unit) {
            $this->unit = $unit;
        }
    }
    public function changeProduct($code)
    {
        $code = str_pad($code, 3, '0', STR_PAD_LEFT);
        $product = Product::whereCode($code)->first();
        $this->units = $product->units->pluck('name', 'pivot.id');
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
        setPDFPath($invoice);
        $this->setTaxes($invoice);
        $this->emit('showAlert', 'Detalle actualizado', 'success');
        $this->emitUp('reloadEdit');
    }
    public function updatePrice()
    {
        $unit = auth()->user()->place->units()->wherePivot('id', $this->unit->pivot['id'])->first();
        if ($this->detail->cant < $unit->pivot->min) {
            $price = $unit->pivot->price_menor;
            $this->detail->price_type = 'detalle';
        } else {
            $price = $unit->pivot->price_mayor;
            $this->detail->price_type = 'mayor';
        }
        $subtotal = $price * $this->detail->cant;
        $taxTotal = $subtotal * $this->product->taxes->sum('rate');
        $total = $subtotal + $taxTotal;
        $this->detail->unit_id = $unit->id;
        $this->detail->price = $price;
        $this->detail->subtotal = $subtotal;
        $this->detail->taxtotal = $taxTotal;
        $this->detail->total = $total;
        $this->detail->save();
        $this->detail->taxes()->detach($this->prevTaxes);
        $this->detail->taxes()->attach($this->product->taxes->pluck('id')->toArray());
    }
    public function updateUnit()
    {
        $prevUnit = auth()->user()->place->units()->wherePivot('id', $this->prevUnitId)->first();
        $prevUnit->pivot->stock = $prevUnit->pivot->stock + $this->prevCant;
        $prevUnit->pivot->save();
        $unit = auth()->user()->place->units()->wherePivot('id', $this->unit->pivot['id'])->first();
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
        $creditable = auth()->user()->place->counts()->where('code', '400-01')->first();
        if ($invoice->payment->payed >= $diffTax) {
            $creditable2 = auth()->user()->place->cash();
        } else {
            $creditable2 = $invoice->client->contable()->first();
        }

        $debitable2 = auth()->user()->place->counts()->where('code', '202-01')->first();
          /* Ajuste Impuesto */
          if ($diffTax < 0) {
            setTransaction('Ajuste impuestos Fct. ' . $invoice->number, $invoice->payment->ncf ?: $invoice->number, abs($diffTax), $debitable2, $creditable2);
        } else {
            setTransaction('Ajuste impuestos Fct. ' . $invoice->number, $invoice->payment->ncf ?: $invoice->number, $diffTax, $creditable2, $debitable2);
        }
        /* Ajuste Detalle */
        if ($diffRest > 0) {
            setTransaction('Ajuste detalle Fct. ' . $invoice->number, $invoice->payment->ncf ?: $invoice->number, $diffRest, $invoice->client->contable()->first(), $creditable);
        } else {
            setTransaction('Ajuste detalle Fct. ' . $invoice->number, $invoice->payment->ncf ?: $invoice->number, abs($diffRest), $creditable, $invoice->client->contable()->first());
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
