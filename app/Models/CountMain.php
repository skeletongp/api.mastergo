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
        $place_id=auth()->user()?auth()->user()->place->id:1;
        return $this->hasMany(Count::class)->where('place_id', $place_id);
    }
}
