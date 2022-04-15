<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
