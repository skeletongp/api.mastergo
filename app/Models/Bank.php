<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded=[];
    

    public function titular()
    {
        return $this->belongsTo(User::class, 'titular_id');
    }
    public function contable()
    {
        return $this->morphOne(Count::class,'contable');
    }
}
