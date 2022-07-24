<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CotizeController extends Controller
{
    public function index(){
        return view('pages.cotizes.index');
    }
    public function create(){
        return view('pages.cotizes.create');
    }
}
