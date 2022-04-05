<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecursoController extends Controller
{
    public function index()
    {
        return view('pages.recursos.index');
    }
}
