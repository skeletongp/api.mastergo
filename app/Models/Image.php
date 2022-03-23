<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable=[
        'path',
        'imagable_id',
        'imagable_type',
    ];

    public function imageable()
    {
        return $this->morphTo();
    }
}
