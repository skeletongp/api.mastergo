<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Income extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable=[
        'amount',
        'concepto',
        'fileable_id',
        'fileable_type',
        'store_id',
        'place_id',
        'user_id',
    ];

    public function incomeable()
    {
        return $this->morphTo();
    }
}
