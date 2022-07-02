<?php

namespace App\Http\Controllers;

use App\Models\Comprobante;
use Illuminate\Http\Request;

class ComprobanteController extends Controller
{
   
    public function __construct() {
        $this->middleware(['permission:Ver Comprobantes'])->only('index');
        $this->middleware(['permission:Crear Comprobantes'])->only('create');
        $this->middleware(['permission:Editar Comprobantes'])->only('edit');
        $this->middleware(['permission:Borrar Comprobantes'])->only('destroy');
    }

    public function index()
    {
       return view('pages.comprobantes.index');
    }

   
    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
        //
    }

    
    public function show(Comprobante $comprobante)
    {
        //
    }

   
    public function edit(Comprobante $comprobante)
    {
        //
    }

   
    public function update(Request $request, Comprobante $comprobante)
    {
        //
    }

    
    public function destroy(Comprobante $comprobante)
    {
        //
    }
}
