<?php

namespace App\Http\Controllers;

use App\Models\Proceso;
use App\Models\Production;
use Illuminate\Http\Request;

class ProcesoController extends Controller
{
    public function index()
    {
        return view('pages.procesos.index');
    }
    public function create()
    {
        return view('pages.procesos.create');
    }
    public function formula(Proceso $proceso)
    {
        return view('pages.procesos.formula-proceso',['proceso'=>$proceso]);
    }
    public function show(Proceso $proceso)
    {
        return view('pages.procesos.show',['proceso'=>$proceso]);
    }
    public function production_show($id)
    {
        $production=Production::whereId($id)->with('recursos')->first();
        return view('pages.productions.show',['production'=>$production]);
    }
}
