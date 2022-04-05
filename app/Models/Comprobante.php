<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comprobante extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable=[
        'type',
        'prefix',
        'number',
        'status',
        'user_id',
        'store_id',
        'place_id',
        'client_id'
    ];
}
