<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductPlaceUnit extends Pivot
{
    protected $table="product_place_units";
    protected $guarded=[];
    protected $connection="mysql";
    public function stock() : Attribute
    {
        return new Attribute(
            get:fn($value)=>$value
        );
    }
    public function price_menor() : Attribute
    {
        return new Attribute(
            get:fn($value)=>$value
        );
    }
    public function cost() : Attribute
    {
        return new Attribute(
            get:fn($value)=>$value
        );
    }

    public function provisions() {
        return $this->hasMany(Provision::class, 'provisionable_id','product_id');
    }

    public function details() {
        return $this->hasMany(Detail::class, 'product_id','product_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
