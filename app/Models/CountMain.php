<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CountMain extends Model
{
    use HasFactory, SoftDeletes;
    
    public function counts()
    {
        return $this->hasMany(Count::class);
    }
}
