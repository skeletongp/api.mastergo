<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scope extends Model
{
    use HasFactory;
    protected $fillable=[
        'name'
    ];

    public function scopeable()
    {
        return $this->morphTo();
    }
}
