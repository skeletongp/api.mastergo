<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Place extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable=[
        'name','phone','uid','user_id','store_id'
    ];
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uid = (string) Uuid::uuid4();
        });
    }

    
    function user()
    {
        return $this->belongsTo(User::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_place_units')->distinct('product_id');
    }
    public function units()
    {
        return $this->belongsToMany(Unit::class, 'product_place_units')->withPivot('price','stock','cost','id');
    }
}
