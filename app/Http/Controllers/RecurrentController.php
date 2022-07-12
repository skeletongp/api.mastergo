<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecurrentController extends Controller
{
    public function index()
    {
        return view('pages.recurrents.index');
    }
}
