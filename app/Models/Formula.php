<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Formula extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded=[];

    function proceso()
    {
        return $this->belongsTo(Proceso::class);
    }
    function formulable()
    {
        return $this->morphTo();
    }
    function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    function place()
    {
        return $this->belongsTo(Place::class);
    }
    function user()
    {
        return $this->belongsTo(User::class);
    }
}
