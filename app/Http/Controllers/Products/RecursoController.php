<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Recurso;
use Illuminate\Http\Request;

class RecursoController extends Controller
{
    public function index()
    {
        return view('pages.products.recursos.index');
    }
    public function show(Recurso $recurso)
    {
        return view('pages.products.recursos.show', ['recurso'=>$recurso]);
    }
    public function sum()
    {
        return view('pages.products.recursos.sum');
    }
}
