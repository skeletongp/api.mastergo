<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provision extends Model
{
    use HasFactory;
    protected $guarded=[];
    const LETTER=['A','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];

   

    public function provisionable()
    {
        return $this->morphTo();
    }
    public function atribuible()
    {
        return $this->morphTo();
    }
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
