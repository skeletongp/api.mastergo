<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //method that show view with all categories
    public function index()
    {
        $categories = Category::all();
        return view('pages.categories.index', compact('categories'));
    }
    
    //method that show vie with category data
    public function show(Category $category)
    {
        return view('pages.categories.show', compact('category'));
    }
}
