<?php

namespace App\Http\Livewire\Invoices;

use App\Http\Helper\Universal;
use App\Models\Client;
use App\Models\Count;
use App\Models\Filepdf;
use App\Models\Invoice;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class OrderConfirm extends Component
{
    public  $form, $compAvail = true;

    public function mount($invoice)
    {
        $this->form = $invoice;
        $this->form=array_merge($this->form, $invoice['payment']);
        $this->form['efectivo'] = $this->form['rest'];
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
    public function setPendiente()
    {
        $dif = $this->form['total'] - $this->form['payed'];
        if ($dif > 0) {
            $this->form['rest'] = round($dif, 2);
            $this->form['cambio'] = 0;
        } else if ($dif < 0) {
            $this->form['cambio'] = 0 -  round($dif, 2);
            $this->form['rest'] = 0;
        } else {
            $this->form['rest'] = 0;
            $this->form['cambio'] = 0;
        }
    }

   
    public function payInvoice()
    {
        $this->validate(orderConfirmRules());
        $invoice = Invoice::find($this->form['id']);
        if ($invoice->status!=='waiting') {
            $this->emit('showAlert','Esta factura ya fue cobrada. Recargue la vista','warning');
            return;
        }
        if ($invoice->condition=='De Contado' && $this->form['rest']>0) {
            $this->emit('showAlert','Está factura debe ser saldada', 'warning');
            return ;
        }

        if ($this->form['rest'] <= 0 && $this->form['status'] != 'entregado') {
            $this->form['status'] = 'pagado';
            $this->form['condition'] = 'De Contado';
        } else {
            $this->form['status'] = 'adeudado';
        }
        $pagos = ['Efectivo' => $invoice->efectivo, 'Tarjeta' => $invoice->tarjeta, 'Transferencia' => $invoice->transferencia];
        $this->form['payway'] = array_search(max($pagos), $pagos);
        $payment=$invoice->payment;
        $invoice->update(Arr::only($this->form, ['note', 'status', 'payway', 'contable_id']));
        $payment->update(Arr::only($this->form, ['efectivo', 'tarjeta', 'transferencia', 'payed', 'rest','cambio']));
        if ($payment->rest > 0) {
            setContable($invoice->client, '101');
        }
        setIncome($invoice, 'Ingreso por venta Factura Nº. ' . $invoice->number, $payment->payed);
        if ($invoice->comprobante) {
            $this->setTaxes($invoice);
        }
        $this->setTransaction($invoice, $payment, $invoice->client);
        setPDFPath($invoice);

        $this->closeComprobante($invoice->comprobante, $invoice);
        $this->emit('refreshLivewireDatatable');
        return redirect()->route('invoices.index');
    }
    public function closeComprobante($comprobante, $invoice)
    {
        if ($comprobante) {
            $comprobante->update([
                'status' => 'usado',
                'user_id' => $invoice->contable_id,
                'client_id' => $invoice->client_id,
            ]);
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
                        'amount' => DB::raw('amount +' . $tax->rate * $detail->subtotal)
                    ]
                );
            }
        }
    }

    public function setTransaction($invoice, $payment, $client)
    {
        $place = auth()->user()->place;
        $creditable = $place->counts()->where('code', '400-01')->first();
        $ref = $invoice->comprobante ?: $invoice;
        $ref = $ref->number;
        $moneys = array($payment->efectivo, $payment->tarjeta, $payment->transferencia, $payment->rest);
        $max = array_search(max($moneys), $moneys);
        $toTax=null;
        switch ($max) {
            case 0:
                setTransaction('Reg. venta a ' . $invoice->client->fullname, $ref, $moneys[$max] - $invoice->tax, $place->cash(), $creditable);
                $toTax=$place->cash();
                break;
            case 1:
                setTransaction('Reg. venta a ' . $invoice->client->fullname, $ref, $moneys[$max] - $invoice->tax, $place->check(), $creditable);
                $place->check();
                break;
            case 2:
                setTransaction('Reg. venta a ' . $invoice->client->fullname, $ref, $moneys[$max] - $invoice->tax, $place->bank(), $creditable);
                $toTax=$place->bank();
                break;
            case 3:
                setTransaction('Reg. venta a ' . $invoice->client->fullname, $ref, $moneys[$max] - $invoice->tax, $client->contable()->first(), $creditable);
                $toTax=$client->contable()->first();
                break;
            default:
            setTransaction('Reg. venta a ' . $invoice->client->fullname, $ref, $moneys[$max] - $invoice->tax, $place->other(), $creditable);
            $place->check();
            break;
        }

        $moneys[$max] = 0;
        setTransaction('Reg. venta en Efectivo', $ref,  $moneys[0] - $invoice->cambio, $place->cash(), $creditable);
        setTransaction('Reg. venta por Cheque', $ref,  $moneys[1], $place->check(), $creditable);
        setTransaction('Reg. venta por Transferencia', $ref,  $moneys[2], $place->bank(), $creditable);
        setTransaction('Reg. venta a Crédito', $ref, $moneys[3],  $client->contable()->first(), $creditable);
        foreach ($invoice->taxes as $tax) {
            setTransaction('Reg. retención de ' . $tax->name, $ref, $tax->pivot->amount,   $toTax, $tax->contable()->first());
        }
        $client->update([
            'limit' => $client->limit - $invoice->payment->rest
        ]);
    }
}