<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;
protected $connection="mysql";
    protected $fillable = [
        'name',
        'description',
        'store_id',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class,'category_products' );
    }

}
