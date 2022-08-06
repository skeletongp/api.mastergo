<?php

namespace App\Http\Controllers;

use App\Models\Cotize;
use Illuminate\Http\Request;

class CotizeController extends Controller
{
    public function index(){
        return view('pages.cotizes.index');
    }
    public function create(){
        return view('pages.cotizes.create');
    }
    public function show(Cotize $cotize){
        $url=$cotize->pdf->pathLetter;
        return view('pages.cotizes.show',compact('url'));
    }
}
