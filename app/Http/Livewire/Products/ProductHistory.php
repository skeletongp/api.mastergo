<?php

namespace App\Http\Livewire\Products;

use Livewire\Component;

class ProductHistory extends Component
{
    public $product;
    public function render()
    {
        return view('livewire.products.product-history');
    }
}
