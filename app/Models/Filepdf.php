<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Filepdf extends Model
{
    use HasFactory, SoftDeletes;
protected $connection="mysql";

    protected $fillable=[
        'note',
        'pathLetter',
        'pathThermal',
        'size',
        'fileable_id',
        'fileable_type',
    ];

    public function fileable()
    {
        return $this->morphTo();
    }
}
