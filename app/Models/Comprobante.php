<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comprobante extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable=[
        'type',
        'prefix',
        'number',
        'status',
        'ncf',
        'user_id',
        'store_id',
        'place_id',
        'client_id'
    ];
    
    
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->ncf = $model->prefix.$model->number;
        });
       
    }
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
