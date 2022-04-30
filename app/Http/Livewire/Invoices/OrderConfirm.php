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
    public  $form, $compAvail = true, $clients;
    public Client $client;

    public function mount($invoice)
    {
        $this->form = $invoice;
        $this->form['efectivo'] = $this->form['rest'];
        $this->updatedForm($this->form['efectivo'], 'efectivo');
    }
    public function render()
    {
        /*   dd($this->getListeners()); */
        if (Cache::get('clients' . auth()->user()->store->id)) {
            $clients = Cache::get('clients' . auth()->user()->store->id);
        } else {
            $clients = auth()->user()->store->clients;
            Cache::put('clients' . auth()->user()->store->id, $clients);
        }
        $this->clients = $clients->pluck('fullname', 'id');
        return view('livewire.invoices.order-confirm');
    }
    public function updatedForm($value, $key)
    {
        switch ($key) {
            case 'type':
                if ($value != 'B00') {
                    $this->compAvail = $this->checkComprobante($value);
                    if (!$this->compAvail) {
                        $this->form['type'] = 'B00';
                    }
                }
                if ($this->form['type'] !== 'B00' && $this->form['type'] !== 'B14') {
                    $taxTotal = floatval(array_sum(array_column($this->form['details'], 'taxtotal')));
                    $this->form['tax'] = round($taxTotal, 2);
                } else {
                    $this->form['tax'] = 0;
                }
                $this->form['efectivo'] =  round($this->form['amount'] + $this->form['tax'] - $this->form['discount'], 2);
                $this->updatedForm(round($this->form['efectivo'], 2), 'efectivo');
                break;
            case 'efectivo':
            case 'tarjeta':
            case 'transferencia':
                $this->form['payed'] = $this->form['efectivo'] +
                    $this->form['tarjeta'] + $this->form['transferencia'];
                break;
            case 'client_id':
                if ($value) {
                    $this->client = Client::find(intval($value));
                }
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

    public function checkComprobante($type): bool
    {
        $comprobante = auth()->user()->store->comprobantes()
            ->where('type', array_search($type, Invoice::TYPES))->where('status', 'disponible')
            ->orderBy('number')->first();
        if ($comprobante) {
            $this->form['comprobante_id'] = $comprobante->id;
            $this->form['type'] = $type;
            return true;
        } else {
            $this->form['type'] = 'B00';
            return false;
        };
    }
    public function payInvoice()
    {
        $this->validate(orderConfirmRules());
        if (!verifyClientLimit($this->client, $this->form['rest'])) {
            $this->emit('showAlert', 'El cliente no tiene crédito disponible', 'warning');
            return;
        }
        $invoice = Invoice::find($this->form['id']);

        if ($this->form['rest'] <= 0 && $this->form['status'] != 'entregado') {
            $this->form['status'] = 'pagado';
        } else {
            $this->form['status'] = 'adeudado';
        }
        $pagos = ['Efectivo' => $invoice->efectivo, 'Tarjeta' => $invoice->tarjeta, 'Transferencia' => $invoice->transferencia];
        $this->form['payway'] = array_search(max($pagos), $pagos);
        $invoice->update(Arr::except($this->form, ['seller', 'pivot', 'details', 'id']));
        if ($invoice->rest > 0) {
            setContable($invoice->client, '101');
        }
        setIncome($invoice, 'Ingreso por venta Factura Nº. ' . $invoice->number, $invoice->payed);
        if ($invoice->comprobante) {
            $this->setTaxes($invoice);
        }
        $this->setTransaction($invoice, $invoice->client);
        setPDFPath($invoice);

        $this->closeComprobante($invoice->comprobante, $invoice);
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

    public function setTransaction($invoice, $client)
    {
        $place = auth()->user()->place;
        $creditable = $place->counts()->where('code', '400-01')->first();
        $ref = $invoice->comprobante ?: $invoice;
        $ref = $ref->number;
        $moneys = array($invoice->efectivo, $invoice->tarjeta, $invoice->transferencia, $invoice->rest);
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
            'limit' => $client->limit - $invoice->rest
        ]);
    }
}
