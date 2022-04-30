<?php

namespace App\Models;

use App\Http\Helper\Universal;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class Recurso extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable=[
        'name',
        'description',
        'cant',
        'cost',
        'store_id',
        'place_id',
        'unit_id',
        'provider_id',

    ];  
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uid = (string) Uuid::uuid4();
        });
    }

    public function unit()
    {   
        return $this->belongsTo(Unit::class, 'unit_id','id');
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function place()
    {
        return $this->belongsTo(Place::class);
    }
    public function procesos()
    {
       return $this->belongsToMany(Proceso::class, 'proceso_recursos');
    }
    public function getCostPriceAttribute()
    {
        return '$'.formatNumber($this->cost);
    }
    public function scopeTotal($query)
    {
       $query->addSelect(DB::raw('cant*cost as Total'));
    }
}
