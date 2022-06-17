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
}
