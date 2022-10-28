<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pesada extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    
    public function pesable()
    {
        return $this->morphTo();
    }
}
