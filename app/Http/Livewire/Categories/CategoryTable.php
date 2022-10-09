<?php

namespace App\Http\Livewire\Categories;

use App\Models\Category;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class CategoryTable extends LivewireDatatable
{
    public $headTitle="Registro de categorías";
    public $padding='px-2';

    public function builder()
    {
        $categories=
        Category::where('categories.store_id', env('STORE_ID'))
        ->leftJoin('category_products', 'categories.id', '=', 'category_products.category_id')
        ->leftJoin('products', 'category_products.product_id', '=', 'products.id')
        ->groupBY('categories.id')

        ;
        return $categories;

    }

    public function columns()
    {
        return [
            Column::callback('categories.id', function ($id) {
                return view('components.view', ['url' => route('categories.show',$id)]);
            }),
            Column::name('name')->label('Nombre')->searchable(),
            Column::callback(['description'], function ($description) {
                return ellipsis($description, 50);
            })->label('Descripción')->searchable(),
            Column::raw('count(products.id) AS products_count')->label('Prod.'),
            Column::delete()->label('Eliminar')->unsortable(),
        ];
    }
}