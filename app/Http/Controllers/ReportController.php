<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function incomes()
    {
        return view('pages.reports.incomes');
    }
    public function outcomes()
    {
        return view('pages.reports.outcomes');
    }
    public function invoices(){
        return view('pages.reports.invoices');
    }
    public function invoices_por_cobrar(){
        return view('pages.reports.invoices_por_cobrar');
    }
}
