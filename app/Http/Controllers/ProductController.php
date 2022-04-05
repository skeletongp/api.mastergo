<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('pages.products.index');
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
