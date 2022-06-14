<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class Provider extends Model
{
    use HasFactory, SoftDeletes, SearchableTrait;


    protected $fillable = [
        'name',
        'lastname',
        'email',
        'fullname',
        'address',
        'rnc',
        'phone',
        'limit',
        'store_id',
    ];
    protected $searchable = [
        
        'columns' => [
            'name' => 10,
            'lastname' => 5,
            'email' => 1,
        ]
    ];
    public static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->fullname = $model->name.' '.(string) rtrim($model->lastname);
        });
        self::updating(function($model){
            $model->fullname = $model->name.' '.(string) rtrim($model->lastname);
        });
    }
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function contable()
    {
        return $this->morphOne(Count::class,'contable');
    }
    public function avatar(): Attribute
    {
        return new Attribute(
            get: fn () => $this->image?$this->image->path:env('NO_IMAGE')
        );
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function provisions()
    {
        return $this->hasMany(Provision::class);
    }

}
