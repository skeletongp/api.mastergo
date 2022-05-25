<?php

namespace App\Http\Livewire\Invoices\Includes;

use Illuminate\Support\Facades\DB;

trait OrderContable
{
    public function setTransaction($invoice, $payment, $client)
    {
        $place = auth()->user()->place;
        $creditable = $place->counts()->where('code', '400-01')->first();
        $discount = $place->counts()->where('code', '401-01')->first();
        $ref = $invoice->comprobante ?: $invoice;
        $ref = $ref->number;
        $moneys = array($payment->efectivo, $payment->tarjeta, $payment->transferencia, $payment->rest);
        $max = array_search(max($moneys), $moneys);
        $toTax = null;
        switch ($max) {
            case 0:
                setTransaction('Reg. venta Ref. Nº. ' . $ref, $ref, $moneys[$max] - $payment->tax, $place->cash(), $creditable);
                $toTax = $place->cash();
                break;
            case 1:
                setTransaction('Reg. venta Ref. Nº. ' . $ref, $ref, $moneys[$max] - $payment->tax, $place->check(), $creditable);
                $toTax =  $place->check();
                break;
            case 2:
                setTransaction('Reg. venta Ref. Nº. ' . $ref, $this->reference, $moneys[$max] - $payment->tax, $this->bank->contable()->first(), $creditable);
                $toTax =  $this->bank->contable()->first();
                break;
            case 3:
                setTransaction('Reg. venta Ref. Nº. ' . $ref, $ref, $moneys[$max] - $payment->tax, $client->contable()->first(), $creditable);
                $toTax = $client->contable()->first();
                break;
           
        }

        $moneys[$max] = 0;
        setTransaction('Reg. venta en Efectivo', $ref,  $moneys[0], $place->cash(), $creditable);
        setTransaction('Reg. vuelto de cambio', $ref,  $payment->cambio, $creditable, $place->cash());
        setTransaction('Reg. venta por Cheque', $ref,  $moneys[1], $place->check(), $creditable);
        setTransaction('Reg. venta por Transferencia', $ref.' | '.$this->reference,  $moneys[2], optional($this->bank)->contable, $creditable);
        setTransaction('Reg. venta a Crédito', $ref, $moneys[3],  $client->contable()->first(), $creditable);
        setTransaction('Descuento a Fct. '.$invoice->number, $ref, $payment->discount,  $discount, $creditable);
        foreach ($invoice->taxes as $tax) {
            setTransaction('Reg. retención de ' . $tax->name, $ref, $tax->rate*$payment->amount,   $toTax, $tax->contable()->first());
        }
        $client->update([
            'limit' => $client->limit - $invoice->payment->rest
        ]);
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
}
