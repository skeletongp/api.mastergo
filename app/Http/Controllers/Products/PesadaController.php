<?php

namespace App\Http\Controllers\Products;

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PesadaController extends Controller
{
    public function index()
    {
        return view('pages.products.pesadas.index');
    }

    public function create()
    {
        return view('pages.products.pesadas.create');
    }
}
