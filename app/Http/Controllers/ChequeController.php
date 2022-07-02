<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChequeController extends Controller
{
    public function __construct() {
        $this->middleware(['permission:Ver Cheques'])->only('index');
    }
    public function index()
    {
        return view('pages.cheques.index');
    }
}
