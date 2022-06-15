<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scope extends Model
{
    use HasFactory;
protected $connection="mysql";

    protected $primaryKey="name";
    public $incrementing = false;
    protected $keyType = 'string';  
    
    protected $fillable=[
        'name',
        'created_at',
        'updated_at'
    ];

    public function scopeable()
    {
        return $this->morphTo();
    }
}
