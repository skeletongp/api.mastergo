<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:Ver Facturas'])->only(['index','show']);
        $this->middleware(['permission:Crear Facturas'])->only('create');
        $this->middleware(['permission:Cobrar Facturas'])->only('orders');
    }

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
        $invoice=$invoice->load('client','client.invoices','details','details.product','details.unit','seller','contable','pdf','incomes', 'payments', 'payments.image');
       return  view('pages.invoices.show', compact('invoice'));
    }
}
