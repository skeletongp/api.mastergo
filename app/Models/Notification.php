<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory, SoftDeletes;

    //set guarded empty
    protected $guarded = [];

    //get place
    public function place()
    {
        return $this->belongsTo('App\Models\Place');
    }
    
}
