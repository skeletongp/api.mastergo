<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cheque extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded=[];
    protected $connection="mysql";
    function user(){
        return $this->belongsTo(User::class);
    }
    function bank(){
        return $this->belongsTo(Bank::class);
    }
    function chequeable(){
        return $this->morphTo();
    }

}
