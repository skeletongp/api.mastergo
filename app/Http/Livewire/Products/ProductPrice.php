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
        $mins=$this->product->units()->pluck('min','name');
        $price=$this->product->units()->pluck('price_menor','name');
        $price2=$this->product->units()->pluck('price_mayor','name');
        $taxes=$this->product->taxes()->pluck('rate','name');
        foreach($price as $in=> $priz){
            $wTax=[];
            $wTax2=[];
            $price[$in]=
            '<div class="flex justify-between items-center "><b>PRECIO DETALLE </b> $'.formatNumber($priz).'</div>' .
            '<div class="flex justify-between items-center "><b>PRECIO x Mayor </b> $'.formatNumber($price2[$in]).'</div>'.
            '<div class="flex justify-between items-center border-b-2 border-gray-500 "><b> MIN x MAYOR </b>'.formatNumber($mins[$in]).'</div>' 

            ;
            foreach ($taxes as $ind=> $tax) {
                $wTax[$ind]=$priz*$tax;
            }
            foreach ($taxes as $ind=> $tax) {
                $wTax2[$ind]=$price2[$in]*$tax;
            }
            foreach ($wTax as $key => $value) {
                $price[$in]=$price[$in].'<div class="flex justify-between items-center"> <b>'.$key.' DETALLE</b>  $'.formatNumber($value).' </div>';
            }
            foreach ($wTax2 as $key => $value) {
                $price[$in]=$price[$in].'<div class="flex justify-between items-center"> <b>'.$key.' x MAYOR</b>  $'.formatNumber($value).' </div>';
            }
           if (count($wTax)) {
            $price[$in]=$price[$in].'<br> <hr> <div class="flex justify-between items-center"><b>TOTAL DETALLE</b> <b>$'.formatNumber(($priz+array_sum($wTax))).'</b></div>';
            $price[$in]=$price[$in].'<div class="flex justify-between items-center"><b>TOTAL x MAYOR</b> <b>$'.formatNumber(($price2[$in]+array_sum($wTax2))).'</b></div>';
           }
        }
        return $price;
    }
}
