<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded=[];
    protected $connection="mysql";
    public function titular()
    {
        return $this->belongsTo(User::class, 'titular_id');
    }
    public function contable()
    {
         $place_id=1;
        if (auth()->user()) {
            $place_id=auth()->user()->place->id;
        }
        return $this->morphOne(Count::class,'contable')->where('place_id',$place_id);;
    }
}
