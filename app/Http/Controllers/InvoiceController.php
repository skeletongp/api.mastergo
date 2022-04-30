<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class InvoiceController extends Controller
{
    public function index()
    {
        return view('pages.invoices.index');
    }
    public function create()
    {
        return view('pages.invoices.create');
    }
    public function orders()
    {
        return view('pages.invoices.orders');
    }
    public function show(Invoice $invoice)
    {
        $data = [
            'invoice' => $invoice,
        ];
        $pdf=App::make('dompdf.wrapper');
        $pdf->loadview('pages.invoices.letter', $data)->setPaper('80mm', 'portrait');
       return  $pdf->stream('invoice.pdf');
    }
}
