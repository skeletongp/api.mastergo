<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Condiment extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    protected $connection = "mysql";

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
           
                $store = Store::find(env('STORE_ID'));
                $num = $store->condiments()->withTrashed()->count() + 1;
                $code = str_pad($num, 3, '0', STR_PAD_LEFT);
                $model->code = $code;
        });
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    public function formulas()
    {
        return $this->morphMany(Formula::class, 'formulable');
    }
    public function provisions()
    {
        return $this->morphMany(Provision::class, 'provisionable');
    }
    function name(): Attribute
    {
        return  Attribute::make(
            set: fn ($value) =>
            $value . ' (COND)'

        );
    }
}
