<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        return view('pages.products.index');
    }
    public function report(){
        $place = auth()->user()->place;
        $store = auth()->user()->store;
        $products = $place->products()->with('units')->get();
        $totalValor = $place->units()->sum(DB::raw('stock * cost'));
        $totalSalida = $place->units()->sum(DB::raw('stock * price_menor'));
        $PDF = App::make('dompdf.wrapper');
        $data = [
            'products' => $products,
            'store' => $store,
            'totalValor' => $totalValor,
            'totalSalida' => $totalSalida,

        ];
        $pdf = $PDF->loadView('pages.products.product-report', $data);
        return $pdf->download('inventario general.pdf');
    }
    public function create()
    {
        return view('pages.products.create');
    }
    public function show(Product $product)
    {
        return view('pages.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('pages.products.edit', with(['product'=>$product]));
    }
    public function sum()
    {
        return view('pages.products.sum');
    }
}
