<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Condiment extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded=[];
    
    protected $connection="mysql";

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
     public function formulas()
    {
        return $this->morphMany(Formula::class, 'formulable');
    }
    public function provisions()
   {
       return $this->morphMany(Provision::class, 'provisionable');
   }
}
