<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CondimentProduction extends Model
{
    
    use HasFactory;
    protected $guarded = [];
    protected $connection = "mysql";
    protected $table="condiment_productions";

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->total = $model->cant * $model->cost;
        });
        static::updating(function ($model) {
            $model->total = $model->cant * $model->cost;
        });
    }
    function production()
    {
        return $this->belongsTo(Production::class);
    }
}
