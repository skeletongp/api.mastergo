<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cuadre extends Model
{
    use HasFactory, SoftDeletes;
protected $connection="mysql";
    protected $guarded=[];

    public function place(){
        return $this->belongsTo(Place::class);
    }
    public function pdf()
    {
        return $this->morphOne(Filepdf::class, 'fileable');
    }
}
