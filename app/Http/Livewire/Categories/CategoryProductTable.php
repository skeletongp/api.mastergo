<?php

namespace App\Http\Livewire\Categories;

use App\Http\Classes\NumberColumn;
use App\Models\Product;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class CategoryProductTable extends LivewireDatatable
{
    public $category_id, $category_name;

    public $padding="px-2";

    public function builder()
    {
        $this->headTitle="Productos de la categoría: ".$this->category_name;
        $products=
        Product::where('products.store_id', env('STORE_ID'))
        ->leftJoin('category_products', 'products.id', '=', 'category_products.product_id')
        ->where('category_products.category_id', $this->category_id)
        ->leftJoin('product_place_units', 'products.id', '=', 'product_place_units.product_id')

        ->groupBY('products.id');

        return $products;
    }

    public function columns()
    {
        return [
            Column::callback(['products.id'], function ($id){
                return view('components.view', ['url' => route('products.show', $id)]);
            })->label('Ver')->unsortable(),
            Column::name('code')->label('Cód.')->searchable()->defaultSort(true),
            Column::name('name')->label('Nombre')->searchable(),
            NumberColumn::name('product_place_units.price_menor')->label('Precio')->formatear('money'),
            NumberColumn::name('product_place_units.stock')->label('Stock')->formatear('number'),
            Column::delete()->label('Remover')->unsortable(),

        ];
    }
    public function delete($id)
    {
        $product=Product::find($id);
        $product->categories()->detach($this->category_id);
        $this->emit('showAlert', 'Producto removido de la categoría', 'success');
        $this->emit('refreshLivewireDatatable');
    }
}