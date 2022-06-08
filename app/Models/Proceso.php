<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proceso extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'start_at',
        'user_id',
        'place_id'
    ];

   
    public function recursos()
    {
        return $this->belongsToMany(Recurso::class, 'proceso_recursos')->withPivot('cant')->withTimestamps();
    }
    public function productions(){
        return $this->hasMany(Production::class);
    }
   
}
