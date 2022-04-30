<?php

namespace App\Http\Livewire\Products;

use App\Http\Helper\Universal;
use App\Models\Product;
use Illuminate\Support\Collection;
use Livewire\Component;

class ProductPrice extends Component
{
    public Product $product;
    public function render()
    {
        $price=$this->formatedData();
       
        return view('livewire.products.product-price', compact('price'));
    }
    public function formatedData() : Collection
    {
        $price=$this->product->units->pluck('plainPrice','name');
        $taxes=$this->product->taxes->pluck('rate','name');
        foreach($price as $in=> $priz){
            $wTax=[];
            $price[$in]='<div class="flex justify-between items-center border-b-2 border-gray-500"><b>PRECIO</b> $'.formatNumber($priz).'</div>';
            foreach ($taxes as $ind=> $tax) {
                $wTax[$ind]=$priz*$tax;
            }
            foreach ($wTax as $key => $value) {
                $price[$in]=$price[$in].'<div class="flex justify-between items-center"> <b>'.$key.'</b>  $'.formatNumber($value).' </div>';
            }
            $price[$in]=$price[$in].'<br> <hr> <div class="flex justify-between items-center"><b>TOTAL</b> <b>$'.formatNumber(($priz+array_sum($wTax))).'</b></div>';
        }
        return $price;
    }
}
