<?php

use App\Models\Store;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;

function setPDFPath($invoice)
{
    $data = [
        'invoice' => $invoice->load('details', 'payments', 'client', 'seller', 'contable'),
        'payment' => $invoice->payment
    ];
    $PDF = App::make('dompdf.wrapper');
    $pdf = $PDF->setOptions([
        'logOutputFile' => null,
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled' => true
    ])->loadView('pages.invoices.letter', $data);
    //delete file if exists
    $name = 'files' . getStore()->id . '/invoices/invoice'.$invoice->id.date('His').'.pdf';
    Storage::disk('digitalocean')->put($name, $pdf->output(), 'public');
    $path = Storage::url($name);
    $pdf = [
        'note' => 'PDF Fact. Nº. ' . $invoice->number,
        'pathLetter' => $path,
    ];

    $invPDF = $invoice->pdf()->updateOrCreate(
        ['fileable_id' => $invoice->id],
        $pdf
    );
   
    $payment = $invoice->payments()->orderBy('id', 'desc')->first();
    $payment->pdf()->updateOrCreate(
        ['fileable_id' => $payment->id],
        $pdf
    );
}
use Twilio\Rest\Client as TwilioClient;
function sendInvoiceWS($path, $phone, $number)
{
    $phone='+1'.preg_replace('/[^0-9]/', '', $phone);
    $client= new TwilioClient(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));
    $client->messages->create(
        'whatsapp:'.$phone,
        [
            'from' => env('TWILIO_FROM_NUMBER'),
            'body' => '¡Hola!, explora nuestro catálogo de productos.',
            "mediaUrl" => [$path],
        ]
    );
}

function setIncome($model, $concepto, $amount)
{
    $store = auth()->user()->store;
    $place = auth()->user()->place;
    $income = $store->incomes()->create(
        [
            'amount' => $amount,
            'concepto' => $concepto,
            'place_id' => $place->id,
            'user_id' => $model->contable_id,
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
    setTransaction('Reg. abono en Efectivo', $ref,  $moneys[0], $place->cash(), $creditable, 'Cobrar Facturas');
    setTransaction('Reg. vuelto de cambio', $ref,  $payment->cambio, $creditable, $place->cash(), 'Cobrar Facturas');
    setTransaction('Reg. abono por Cheque', $ref,  $moneys[1], $place->check(), $creditable, 'Cobrar Facturas');
    setTransaction('Reg. abono por Transferencia', $ref . ' | ' . $reference,  $moneys[2], optional($bank)->contable, $creditable, 'Cobrar Facturas');

    
}
