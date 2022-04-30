<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Outcome extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable=[
        'amount','concepto','ref','ncf','user_id','store_id'
    ];
}
