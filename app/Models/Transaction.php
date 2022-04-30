<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable=[
        'concepto', 'ref','day','income','outcome','place_id', 'debitable_id','creditable_id'
    ];

    public function debe()
    {
       return $this->belongsTo(Count::class, 'debitable_id');
    }
    public function haber()
    {
        return $this->belongsTo(Count::class, 'creditable_id');
    }
}
