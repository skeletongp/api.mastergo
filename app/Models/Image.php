<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use HasFactory, SoftDeletes;
protected $connection="mysql";
    protected $fillable = [
        'path',
        'imageable_id',
        'imageable_type',
        'created_by',
        'updated_by',
        
    ];

    public function imageable()
    {
        return $this->morphTo();
    }

   
}
