<?php

namespace App\Models;

use App\Http\Helper\Universal;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;


class Recurso extends Model implements Searchable
{
    use HasFactory, SoftDeletes, SearchableTrait;
    protected $connection = "mysql";

    protected $fillable = [
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
            $store=Store::find($model->store_id);
            $num=$store->recursos()->count()+1;
            $code=str_pad($num,3,'0', STR_PAD_LEFT);
            $model->code=$code;
        });
    }
   
    public function getSearchResult(): SearchResult
    {
       $url = route('recursos.show', $this->id);
    
        return new SearchResult(
           $this,
           $this->code.' '.$this->name,
           $url
        );
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function place()
    {
        return $this->belongsTo(Place::class);
    }
    public function brands()
    {
        return $this->hasMany(Brand::class);
    }
    public function provisions()
    {
        return $this->morphMany(Provision::class, 'provisionable');
    }
    public function contable()
    {
        return $this->morphMany(Count::class, 'contable');
    }
    public function formulas()
    {
        return $this->morphMany(Formula::class, 'formulable');
    }
    function name(): Attribute
    {
        return  Attribute::make(
            set: fn ($value) =>
            $value . ' (MP)'

        );
    }
}
