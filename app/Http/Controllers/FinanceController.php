<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
   public function index()
   {
       return view('pages.finances.index');
   }
   public function bank_show(Bank $bank, $type){
        $bank=$bank;
        $type=$type;
         return view('pages.finances.bank_show', compact('bank', 'type'));
   }
}
