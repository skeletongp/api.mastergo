<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
   public function general_daily()
   {
       return view('pages.reports.general_daily');
   }
   public function general_mayor()
   {
       
       return view('pages.reports.general_mayor');
   }
}
