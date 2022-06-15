<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CountMain extends Model
{
    use HasFactory, SoftDeletes;
protected $connection="mysql";
    const CLASE=[
        '1'=>'Activo',
        '2'=>'Pasivo',
        '3'=>'Capital',
        '4'=>'Ingreso',
        '5'=>'Costo',
        '6'=>'Gasto',
    ];
    public function counts()
    {
        $place_id=auth()->user()?auth()->user()->place->id:1;
        return $this->hasMany(Count::class)->where('place_id', $place_id);
    }
}
