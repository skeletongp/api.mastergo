<?php

namespace App\Models;

use App\Http\Helper\Universal;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tax extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable=[
        'name',
        'rate',
        'store_id'
    ];
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
    
    public function name() : Attribute
    {
        return new Attribute(
            set:fn($value)=>strtoupper($value)
        );
    }
    public function contable()
    {
        return $this->morphMany(Count::class, 'contable');
    }
}
