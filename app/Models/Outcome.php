<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Outcome extends Model
{
    use HasFactory, SoftDeletes;
protected $connection="mysql";

    protected $guarded=[
        
    ];

    public function outcomeable()
    {
        return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function payment()
    {
        return $this->morphOne(Payment::class, 'payable');
    }
    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }
}
