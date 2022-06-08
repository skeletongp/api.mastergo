<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductPlaceUnit extends Pivot
{
    protected $table="product_place_units";
    protected $guarded=[];
   
    public function stock() : Attribute
    {
        return new Attribute(
            get:fn($value)=>$value
        );
    }
}
