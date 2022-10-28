<?php

namespace App\Http\Controllers\Products;;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ProvisionController extends Controller
{
    public function index(){
        return view('pages.products.provisions.index');
    }
}
