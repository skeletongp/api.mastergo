<?php

namespace App\Http\Livewire\Categories;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class AddCategoryProduct extends Component
{
    public $products;
    public $category_id;
    public $product_code;
    public $product_name;
    public $selectedProducts = [];

    public function render()
    {
        $this->products=getProductsWithCode();
        return view('livewire.categories.add-category-product');
    }

    //on updated producto code, get producto from db by code and add name and id to selectedproducts array
    public function updatedProductCode()
    {
        $product = Product::where('code', $this->product_code)->first();
        if ($product) {
            $this->product_name = $product->name;
            $this->selectedProducts[$product->id] = $product->name;
        }
    }

    //remove product from selectedproducts array
    public function removeProduct($id)
    {
        unset($this->selectedProducts[$id]);
    }

    //save selected products to category
    public function addCategoryProducts()
    {
        $category = Category::findOrFail($this->category_id);
        //add productos to category if not already added
        foreach ($this->selectedProducts as $key => $value) {
            $category->products()->syncWithoutDetaching($key);
        }
        $this->emit('refreshLivewireDatatable');
        $this->emit('showAlert', 'Productos agregados con éxito', 'success');
        $this->resetExcept('category_id');
    }


}
