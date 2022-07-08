<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinanceController extends Controller
{
   public function index()
   {
       return view('pages.finances.index');
   }
}
