<?php

use App\Models\Filepdf;
use Illuminate\Support\Facades\App;

function setPDFPath($invoice)
{
    $data = [
        'invoice' => $invoice->load('details','payments','client','seller','contable'),
        'payment'=>$invoice->payment
    ];
    $PDF = App::make('dompdf.wrapper');
    $PDF2 = App::make('dompdf.wrapper');
    $pdf = $PDF->loadView('pages.invoices.letter', $data);
    $pdf2 = $PDF2->loadView('pages.invoices.thermal', $data);
    file_put_contents('storage/invoices/' . $invoice->number.'_'.date('YmdHi'). '_letter.pdf', $pdf->output());
    file_put_contents('storage/invoices/' . $invoice->number.'_'.date('YmdHi'). '_thermal.pdf', $pdf2->output());
    $path = asset('storage/invoices/' . $invoice->number.'_'.date('YmdHi'). '_letter.pdf');
    $path2 = asset('storage/invoices/' . $invoice->number.'_'.date('YmdHi'). '_thermal.pdf');
    $pdf = [
        'note' => 'PDF Fact. Nº. ' . $invoice->number,
        'pathLetter' => $path,
        'pathThermal' => $path2,
    ];
  
    $invoice->pdf()->updateOrCreate(['fileable_id'=>$invoice->id],
        $pdf
    );
    $payment=$invoice->payments()->orderBy('id','desc')->first();
    $payment->pdf()->create(
        $pdf
    );

}

function setIncome($model, $concepto, $amount)
{
   $store=auth()->user()->store;
   $place=auth()->user()->place;
   $income=$store->incomes()->create(
       [
        'amount'=>$amount,
        'concepto'=>$concepto,
        'place_id'=>$place->id,
        'user_id'=>$model->contable_id,
       ]
   );
   $model->incomes()->save($income);
}
 function setPaymentTransaction($invoice, $payment, $client, $bank, $reference)
{
    $place = auth()->user()->place;
    $creditable =  $client->contable()->first();
    $ref = $invoice->comprobante ?: $invoice;
    $ref = $ref->number;
    $moneys = array($payment->efectivo, $payment->tarjeta, $payment->transferencia, $payment->rest);
    $max = array_search(max($moneys), $moneys);
   
    switch ($max) {
        case 0:
            setTransaction('Reg. abono Ref. Nº. ' . $ref, $ref, $moneys[$max], $place->cash(), $creditable, 'Cobrar Facturas');
            break;
        case 1:
            setTransaction('Reg. abono Ref. Nº. ' . $ref, $ref, $moneys[$max], $place->check(), $creditable, 'Cobrar Facturas');
            break;
        case 2:
            setTransaction('Reg. abono Ref. Nº. ' . $ref, $reference, $moneys[$max], $bank->contable()->first(), $creditable, 'Cobrar Facturas');
            break;
    }
    $moneys[$max] = 0;
    setTransaction('Reg. abono en Efectivo', $ref,  $moneys[0] , $place->cash(), $creditable, 'Cobrar Facturas');
    setTransaction('Reg. vuelto de cambio', $ref,  $payment->cambio, $creditable, $place->cash(), 'Cobrar Facturas');
    setTransaction('Reg. abono por Cheque', $ref,  $moneys[1], $place->check(), $creditable, 'Cobrar Facturas');
    setTransaction('Reg. abono por Transferencia', $ref.' | '.$reference,  $moneys[2], optional($bank)->contable, $creditable, 'Cobrar Facturas');
 
    $client->update([
        'limit' => $client->limit + $payment->payed
    ]);
}
