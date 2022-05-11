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
        $invoice=$invoice->with('client','client.invoices','details','seller','contable','pdfs','incomes')->first();
       return  view('pages.invoices.show', compact('invoice'));
    }
}
