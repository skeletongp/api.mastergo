<?php

namespace App\Http\Controllers\Products;;

use App\Http\Controllers\Controller;
use App\Models\Detail;
use App\Models\Product;
use App\Models\ProductPlaceUnit;
use App\Models\Provision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        return view('pages.products.products.index');
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
        $pdf = $PDF->loadView('pages.products.products.product-report', $data);
        return $pdf->download('inventario general.pdf');
    }
    public function create()
    {
        return view('pages.products.products.create');
    }
    public function show(Product $product)
    {
        $provisions = Provision::where('provisionable_id', $product->id)->where('provisionable_type', Product::class)->sum('cant');
            $details = Detail::where('product_id', $product->id)->sum('cant');
            $ppUnit = ProductPlaceUnit::where('product_id', $product->id)->first();
            $ppUnit->update([
                'stock' => $provisions - $details
            ]);
        return view('pages.products.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('pages.products.products.edit', with(['product'=>$product]));
    }
    public function sum()
    {
        return view('pages.products.products.sum');
    }
    public function catalogue(){
         $place = auth()->user()->place;
        $store=auth()->user()->store;
        $products = $place->products()->with('units')->get();

        $PDF = App::make('dompdf.wrapper');
        $data = [
            'products' => $products,

        ];
        $pdf = $PDF->loadView('pages.products.products.catalogue', $data);
        $name = 'files' . $store->id . '/catálogo/catalogo de productos.pdf';
        Storage::disk('digitalocean')->put($name, $pdf->output(), 'public');
        $url = Storage::url($name);
        Cache::put('productCatalogue_'.env('STORE_ID'), $url);
        return view('pages.products.products.view-catalogue', compact('url'));
    }
}
