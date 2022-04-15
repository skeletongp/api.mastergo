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

    protected $fillable = [
        'name',
        'symbol',
        'store_id'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    
    public function symbol() : Attribute
    {
        return new Attribute(
            set: fn($value)=>strtoupper($value)
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
        return $this->belongsToMany(Product::class, 'product_place_units')->withPivot('price','stock','cost','id');
    }

    public function price() : Attribute
    {
        return new Attribute(
            get:fn($value)=> ' $'.Universal::formatNumber($this->pivot->price)
        );
    }
    public function plainPrice() : Attribute
    {
        return new Attribute(
            get:fn()=> $this->pivot->price
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
            get:fn($value)=> Universal::formatNumber($this->pivot->stock)
        );
    }
   
}
