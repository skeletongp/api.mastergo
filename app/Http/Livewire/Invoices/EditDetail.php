<?php

namespace App\Http\Livewire\Invoices;

use App\Http\Livewire\General\Authorize;
use App\Jobs\CreatePDFJob;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class EditDetail extends Component
{

    use Authorize;
    public $place;
    public $detail, $product, $unit;
    public $prevUnitId, $prevCant, $prevTaxes, $diffPayment, $prevRest, $prevInvTax, $prevPrice;
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
        $invoice = $this->detail->detailable->load('details', 'payment', 'client');
        if($invoice->payments()->count()>1){
            $this->emit('showAlert', 'No se puede editar el detalle, ya que la factura tiene pagos realizados','error',3000);
            return;
         }
        if ($this->detail['cant']==$this->prevCant && $this->detail['price']==$this->prevPrice) {
          $this->emit('showAlert', 'No se ha realizado ningun cambio','warning');
          return;
        }
        if ($this->detail['cant']>$this->prevCant || $this->detail['price'] > $this->prevPrice) {
            $this->emit('showAlert', 'No se pueden aumentar los montos','error',3000);
            return;
          }
        $this->updateUnit();
        $this->detail->product_id = $this->product->id;
        $details=$this->updatePrice($invoice);
        $this->updatePayment($invoice, $details);
        
        $this->render();
        $this->setTaxes($invoice);
        dispatch(new CreatePDFJob($invoice))->onConnection('sync');

        $this->emit('showAlert', 'Detalle actualizado', 'success');
        $this->emitUp('reloadEdit');
    }
    public function updatePrice($invoice)
    {
        $unit = $this->place->units()->wherePivot('id', $this->unit->pivot['id'])->first();
        $price=$this->detail->price;
        $subtotal = $price * $this->detail->cant;
        $taxTotal = $subtotal * $this->product->taxes->sum('rate');
        $prevTotal=$this->detail->total;
        $this->prevRest = $invoice->rest;
        if($invoice->type!='B00' && $invoice->type!='B14'){
            $total = $subtotal + $taxTotal;
        } else {
            $total = $subtotal;
        }
        $this->detail->unit_id = $unit->id;
        $this->detail->subtotal = $subtotal;
        $this->detail->taxtotal = $taxTotal;
        $this->detail->total = $total;
        $this->detail->utility = $this->detail->subtotal-($this->detail->cost+$this->detail->cost_service);
        $diffTotal=$prevTotal-$total;
        $this->updateTransaction($invoice, $diffTotal);
        $this->detail->save();
        $this->detail->taxes()->detach($this->prevTaxes);
        $this->detail->taxes()->attach($this->product->taxes()->pluck('taxes.id')->toArray());
        return $this->detail->detailable->details()->get();
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
    public function updatePayment($invoice, $details)
    {
        $payment = $invoice->payment;
       $total=$payment->total;
        
        $payment->amount = $details->sum('subtotal');
        $payment->discount = $details->sum('discount');
        $payment->tax = $details->sum('taxtotal');
        $payment->total = $details->sum('total');
        if ($payment->payed>$payment->total) {
            $payment->cambio = $payment->payed - $payment->total-$payment->rest;
      
            $payment->rest=0;
        } else {
            $payment->rest = $payment->total - $payment->payed;
        }
        $payment->save();
        $this->diffPayment = $total-$payment->total;
        $invoice->update(['rest' => $payment->rest]);
        $this->updateClientLimit($this->prevRest, $invoice->client, $payment);
    }
    public function updateClientLimit($prevRest, $client, $payment)
    {
        $client->limit = $client->limit + $prevRest;
        $client->save();
        $client->limit = $client->limit - $payment->rest;
        $client->debt=$client->invoices->sum('rest');
        $client->save();
    }
    public function updateTransaction($invoice, $diffTotal)
    {
        $tax = $invoice->payment->tax;
        $diffTax =  $tax-$this->prevInvTax;
        if ($this->prevPrice != $this->detail->price) {
            $desc_dev_ventas = $this->place->findCount('401-03');
        } else {
            $desc_dev_ventas = $this->place->findCount('401-01');
        }
            $creditable2 = $this->place->cash();
        

        $debitable2 = $this->place->findCount('203-01');
          /* Ajuste Impuesto */
         if($invoice->type!='B00' && $invoice->type!='B14'){
            if ($diffTax < 0) {
                setTransaction('Ajuste impuestos Fct. ' . $invoice->number, $invoice->payment->ncf ?: $invoice->number, abs($diffTax), $debitable2,  $desc_dev_ventas);
                
            } else {
                setTransaction('Ajuste impuestos Fct. ' . $invoice->number, $invoice->payment->ncf ?: $invoice->number, $diffTax, $creditable2, $debitable2);
            }
         }
        /* Ajuste Detalle */
        $credi=$this->place->cash();
        if($diffTotal<$this->prevRest){
            $credi=$invoice->client->contable;
            setTransaction('Ajuste detalle Fct. ' . $invoice->number, $invoice->payment->ncf ?: $invoice->number, $diffTotal, $desc_dev_ventas, $credi);
            return;
        }
        setTransaction('Ajuste detalle Fct. ' . $invoice->number, $invoice->payment->ncf ?: $invoice->number, $diffTotal-$this->prevRest, $desc_dev_ventas, $credi);
        setTransaction('Saldo deuda vía devolución Fct. ' . $invoice->number, $invoice->payment->ncf ?: $invoice->number, $this->prevRest, $desc_dev_ventas, $invoice->client->contable);
      
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
