<?php

namespace App\Models;

use App\Http\Helper\Universal;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Product extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable=[
        'name',
        'name',
        'description',
        'store_id',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uid = (string) Uuid::uuid4();
        });
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_products');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function units()
    {
        return $this->belongsToMany(Unit::class, 'product_place_units')->withPivot('price','stock','cost');
    }

    public function places()
    {
       return $this->belongsToMany(Place::class, 'product_place_units')->withPivot('name');
    }

    public function taxes()
    {
        return $this->belongsToMany(Tax::class,'product_taxes');
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function photo(): Attribute
    {
        return new Attribute(
            get: fn () => $this->image?$this->image->path:env('NO_IMAGE')
        );
    }
    public function productTaxes()
    {
        return $this->hasManyThrough( ProductTaxes::class, ProductPlaceUnit::class,  'product_id', 'product_id','id','product_id');
    }
    public function rate() : Attribute
    {
        return new Attribute(
            get:fn($value)=> $this->taxes()->sum('rate')
        );
    }
    public function stock():Attribute
    {
        $places=$this->units()->where('place_id', auth()->user()->place->id)->get()->pluck('stock','name','places.name');
        return new Attribute(
            get:fn()=>$places
        );
    }
    public function recursos()
    {
        return $this->belongsToMany(Recurso::class, 'proceso_recursos')->withPivot('cant')->withTimestamps();
    }
    public function procesos()
    {
        return $this->belongsToMany(Proceso::class, 'proceso_product_units')->withPivot('due','obtained','eficiency')->withTimestamps();
    }
    public function procunits()
    {
        return $this->belongsToMany(Unit::class, 'proceso_product_units')->withPivot('due','obtained','eficiency')
        ->withTimestamps();
    }

    public function scopeProcunit($query, $proceso_id)
    {
        return $this->procunits()->where('proceso_id', $proceso_id)->first();
    }
    
    
}
