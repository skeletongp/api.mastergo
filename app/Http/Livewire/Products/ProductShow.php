<?php

namespace App\Http\Livewire\Products;

use App\Models\Product;
use Livewire\Component;

class ProductShow extends Component
{
    public Product $product;
    
    public $componentName='products.product-detail';

    protected $queryString=['componentName'];

    public function render()
    {
        return view('livewire.products.product-show');
    }
    public function setComponent($name)
    {
        $this->componentName=$name;
    }
}
