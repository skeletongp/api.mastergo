<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $connection="mysql";
    protected $guarded=[];

    public function recurso()
    {
        return $this->belongsTo(Recurso::class);
    }
}
