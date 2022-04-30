<?php

use App\Models\Filepdf;
use Illuminate\Support\Facades\App;

function setPDFPath($invoice)
{
    $data = [
        'invoice' => $invoice,
    ];
    $PDF = App::make('dompdf.wrapper');
    $PDF2 = App::make('dompdf.wrapper');
    $pdf = $PDF->loadView('pages.invoices.letter', $data);
    $pdf2 = $PDF2->loadView('pages.invoices.thermal', $data);
    file_put_contents('storage/invoices/' . $invoice->number . '-letter.pdf', $pdf->output());
    file_put_contents('storage/invoices/' . $invoice->number . '-thermal.pdf', $pdf2->output());
    $path = asset('storage/invoices/' . $invoice->number . '-letter.pdf');
    $path2 = asset('storage/invoices/' . $invoice->number . '-thermal.pdf');
    $pdfLetter = Filepdf::create([
        'note' => 'PDF Tamaño carta de la factura ' . $invoice->number,
        'path' => $path,
        'size' => 'letter',
    ]);
    $pdfThermal = Filepdf::create([
        'note' => 'PDF Tamaño térmico de la factura ' . $invoice->number,
        'path' => $path2,
        'size' => 'thermal',
    ]);
    $invoice->pdfs()->save(
        $pdfLetter,
    );
    $invoice->pdfs()->save(
        $pdfThermal,
    );
    $invoice->update([
        'pdfLetter'=>$path,
        'pdfThermal'=>$path2,
    ]);
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
