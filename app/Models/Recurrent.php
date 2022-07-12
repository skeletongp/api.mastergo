<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recurrent extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [
       
    ];
    public function count(){
        return $this->belongsTo(Count::class);
    }
    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }
}
