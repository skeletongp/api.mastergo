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
    

    public static function boot(){
        parent::boot();
        static::creating(function($bank){
            $terminal=substr($bank->bank_number,strlen($bank->bank_number)-4);
            $bank->bank_name.='-'.$terminal;
        });
    }

    public function contable()
    {
         $place_id=1;
        if (auth()->user()) {
            $place_id=auth()->user()->place->id;
        }
        return $this->morphOne(Count::class,'contable')->where('place_id',$place_id);;
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
