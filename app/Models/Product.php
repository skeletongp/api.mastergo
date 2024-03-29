<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Ramsey\Uuid\Uuid;
class Product extends Model implements Searchable
{
    use HasFactory, SoftDeletes;
    protected $connection="mysql";


    protected $appends = ['photo'];
    protected $guarded=[
      
    ];
    protected $searchable = [
        'columns' => [
            'name' => 10,
            'description' => 5,
        ]
    ];
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
          if (auth()->user()) {
            $store=optional(auth()->user())->store;
            $num=$store->products()->withTrashed()->count()+1;
            $code=str_pad($num,3,'0', STR_PAD_LEFT);
            $model->code=$code;
          }
        });
    }

    public function getSearchResult(): SearchResult
    {
       $url = route('products.show', $this->id);
    
        return new SearchResult(
           $this,
           $this->code.' '.$this->name,
           $url
        );
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_products');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function contable()
    {
        return $this->morphMany(Count::class, 'contable');
    }

    public function units()
    {
        return $this->belongsToMany(Unit::class, 'product_place_units')->withPivot('id','price_menor','price_mayor', 'price_special','min','stock','cost','discount');
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
    public function provisions()
    {
        return $this->morphMany(Provision::class, 'provisionable');
    }
    public function productProductions()
    {
        return $this->morphMany(ProductProduction::class, 'productible');
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
    public function name() : Attribute
    {
        return new Attribute(
            set:fn($value)=>preg_replace("/[^A-Za-z0-9- ]/", '', removeAccent($value))
        );
    }
    public function stock():Attribute
    {
        $places=$this->units()->where('place_id', auth()->user()->place->id)->get()->pluck('stock','name');
        return new Attribute(
            get:fn()=>$places
        );
    }
    public function recursos()
    {
        return $this->belongsToMany(Recurso::class, 'proceso_recursos')->withPivot('cant')->withTimestamps();
    }
    public function productions()
    {
        return $this->morphToMany(Production::class, 'productible');
    }
    
    public function providers()
    {
        return $this->belongsToMany(Provider::class, 'product_providers');
    }
    
    public function details()
    {
        return $this->hasMany(Detail::class);
    }
    
}
