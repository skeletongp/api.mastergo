<?php

namespace App\Http\Controllers;

use App\Models\Recurso;
use Illuminate\Http\Request;

class RecursoController extends Controller
{
    public function index()
    {
        return view('pages.recursos.index');
    }
    public function show(Recurso $recurso)
    {
        return view('pages.recursos.show', ['recurso'=>$recurso]);
    }
    public function sum()
    {
        return view('pages.recursos.sum');
    }
}
