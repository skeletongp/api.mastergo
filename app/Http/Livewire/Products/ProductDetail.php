<?php

namespace App\Http\Livewire\Products;

use App\Http\Helper\Universal;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ProductDetail extends Component
{
    public Product $product;
    public function render()
    {
        $price = $this->formatedData();
        return view('livewire.products.product-detail', compact('price'));
    }
    public function formatedData(): Collection
    {
        $price = $this->product->units()->
        select(
            DB::raw("CONCAT(ROUND(stock,2),' → ',name) AS name"),'price_menor')->pluck('pivot.price_menor', 'name');
        $taxes = $this->product->taxes->pluck('rate', 'name');
       
        $wTax = [];
        foreach ($price as $in => $priz) {
            $price[$in] = '$' . formatNumber($priz);
            if ($taxes->count()) {
                foreach ($taxes as $ind => $tax) {
                    $wTax[$ind] = $priz * $tax;
                }
                $price[$in] = $price[$in] . '+$' . formatNumber((array_sum($wTax)));
            } else{
                $price[$in] = $price[$in];
            }
        }
        return $price;
    }
}
