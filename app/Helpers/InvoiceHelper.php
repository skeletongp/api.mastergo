<?php

use App\Models\Store;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
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
    if (file_exists( asset('storage/invoices/' . $invoice->number . '_' . date('Ymd') . '_letter.pdf'))) {
        unlink( asset('storage/invoices/' . $invoice->number . '_' . date('Ymd') . '_letter.pdf'));
    }
    file_put_contents('storage/invoices/' . $invoice->number . '_' . date('Ymd') . '_letter.pdf', $pdf->output());
    $path = asset('storage/invoices/' . $invoice->number . '_' . date('Ymd') . '_letter.pdf');
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
function sendInvoiceWS($path, $phone, $number)
{
    $user=auth()->user();
    $phone='1'.preg_replace('/[^0-9]/', '', $phone);
    $phone2='1'.preg_replace('/[^0-9]/', '', $user->phone);
    $whatsapp_cloud_api = new WhatsAppCloudApi([
        'from_phone_number_id' => env('WHATSAPP_NUMBER_ID'),
        'access_token' => env('WHATSAPP_TOKEN'),
    ]);
    $document_name = basename($path);
    $document_caption =  $number;
    $user=auth()->user();
    $document_link = $path;
    $link_id = new LinkID($document_link);
    $whatsapp_cloud_api->sendDocument($phone, $link_id, $document_name, $document_caption);
    $whatsapp_cloud_api->sendDocument($phone2, $link_id, $document_name, $document_caption);
    $whatsapp_cloud_api->sendTextMessage($phone, 'Adjunto del documento Noº. '.$document_caption);
    $whatsapp_cloud_api->sendTextMessage($phone2, 'Adjunto del documento Noº. '.$document_caption);
    dd($phone, $phone2, $whatsapp_cloud_api->sendTextMessage($phone2, 'Adjunto del documento Noº. '.$document_caption));
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

    $client->update([
        'limit' => $client->limit + $payment->payed,
        'debt' => $client->invoices->sum('rest'),
    ]);
}
