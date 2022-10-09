<?php

namespace App\Models;

use App\Http\Helper\Universal;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use HasFactory, SoftDeletes;
protected $connection="mysql";

    protected $fillable = [
        'name',
        'symbol',
        'store_id',
        'created_by',
        'updated_by',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    
    public function symbol() : Attribute
    {
        return new Attribute(
            set: fn($value)=>strtoupper(str_replace('.','',$value))
        );
    }
    public function name() : Attribute
    {
        return new Attribute(
            set: fn($value)=>ucfirst(strtolower($value))
        );
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_place_units')->withPivot('price_menor','price_mayor', 'price_special','min','stock','cost','id');
    }
    

    public function price() : Attribute
    {
        return new Attribute(
            get:fn($value)=> ' $'.formatNumber($this->pivot->price)
        );
    }
    public function plainPriceMenor() : Attribute
    {
        return new Attribute(
            get:fn()=> $this->pivot->price_menor
        );
    }
    public function plainPriceMayor() : Attribute
    {
        return new Attribute(
            get:fn()=> $this->pivot->price_mayor
        );
    }
    public function plainPriceSpecial() : Attribute
    {
        return new Attribute(
            get:fn()=> $this->pivot->price_special
        );
    }
    public function cost() : Attribute
    {
        return new Attribute(
            get:fn()=> $this->pivot->cost
        );
    }
    public function stock() : Attribute
    {
        return new Attribute(
            get:fn($value)=> formatNumber($this->pivot->stock)
        );
    }
   
}
