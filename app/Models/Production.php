<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    use HasFactory;
    protected $connection = "mysql";
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        static::created(function ($production) {
            $production->update([
                'code' => str_pad($production->id, 4, '0', STR_PAD_LEFT)
            ]);
        });
    }
    public function proceso()
    {
        return $this->belongsTo(Proceso::class);
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    public function recursos()
    {
        return $this->belongsToMany(Recurso::class, 'production_recursos')->withPivot('cant', 'stock', 'status','brand_id')->withTimestamps();
    }
    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'production_recursos')->withPivot('cant', 'stock', 'status');
    }
    function condiments()
    {
        return $this->belongsToMany(Condiment::class, 'condiment_productions')->withPivot('cant', 'cost', 'total', 'attribute')->withTimestamps();
    }

    public function products()
    {
        return $this->hasMany(ProductProduction::class);
    }
}
