<?php

namespace App\Http\Controllers\Products;;

use App\Http\Controllers\Controller;

use App\Models\Proceso;
use App\Models\Production;
use Illuminate\Http\Request;

class ProcesoController extends Controller
{
    public function index()
    {
        return view('pages.products.procesos.index');
    }
    public function create()
    {
        return view('pages.products.procesos.create');
    }
    public function formula(Proceso $proceso)
    {
        return view('pages.products.procesos.formula-proceso',['proceso'=>$proceso]);
    }
    public function show(Proceso $proceso)
    {
        return view('pages.products.procesos.show',['proceso'=>$proceso]);
    }
    public function production_show($id)
    {
        $production=Production::whereId($id)->with('recursos')->first();
        return view('pages.productions.show',['production'=>$production]);
    }
}
