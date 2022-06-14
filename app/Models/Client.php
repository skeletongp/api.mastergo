<?php

namespace App\Models;

use App\Observers\ClientObserver;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Nicolaslopezj\Searchable\SearchableTrait;

class Client extends Model implements Searchable
{
    use HasFactory, SoftDeletes, SearchableTrait;


    protected $fillable = [
        'name',
        'code',
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
    protected $appends=[
        'debt'
    ];

    public function getSearchResult(): SearchResult
    {
       $url = route('clients.index', $this->id);
    
        return new SearchResult(
           $this,
           $this->fullname,
           $url
        );
    }

    public static function boot()
    {
        parent::boot();
        self::observe(new ClientObserver);
    }
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function avatar(): Attribute
    {
        return new Attribute(
            get: fn () => $this->image?$this->image->path:env('NO_IMAGE')
        );
    }
    public function balance(): Attribute
    {
        return new Attribute(
            get: fn () => formatNumber($this->limit)
        );
    }
    public function contable()
    {
        return $this->morphOne(Count::class,'contable');
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
    public function payments()
    {
        return $this->morphMany(Payment::class,'payer');
    }
    public function getDebtAttribute(){
        return $this->invoices()->sum('rest');
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
