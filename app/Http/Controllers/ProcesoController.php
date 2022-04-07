<?php

namespace App\Http\Controllers;

use App\Models\Proceso;
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
    public function show(Proceso $proceso)
    {
        return view('pages.procesos.show',['proceso'=>$proceso]);
    }
}
