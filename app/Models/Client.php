<?php

namespace App\Models;

use App\Observers\ClientObserver;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Nicolaslopezj\Searchable\SearchableTrait;

class Client extends Model implements Searchable
{
    use HasFactory, SoftDeletes, SearchableTrait;

    protected $connection = "mysql";

    protected $with = ['contact', 'store'];

    protected $fillable = [
        'name',
        'code',
        'lastname',
        'email',
        'fullname',
        'special',
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
   

    public function getSearchResult(): SearchResult
    {
        $url = route('clients.show', $this->id);
        return new SearchResult(
            $this,
            $this->name,
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
            get: fn () => $this->image ? $this->image->path : env('NO_IMAGE')
        );
    }
    public function balance(): Attribute
    {
        return new Attribute(
            get: fn () => formatNumber($this->limit)
        );
    }
    public function getNameAttribute()
    {
        return  $this->attributes['name'] ?: ($this->contact->fullname ?? 'Sin nombre');
    }
    public function name(): Attribute
    {
        return new Attribute(
            set: fn ($value) => $this->attributes['name'] = ucwords($value, ' '),
        );
    }
    public function address(): Attribute
    {
        return new Attribute(
            set: fn ($value) => $this->attributes['name'] = ucwords($value, ' '),
        );
    }
    public function email(): Attribute
    {
        return new Attribute(
            set: fn ($value) => $this->attributes['name'] = strtolower($value),
        );
    }
    public function contable()
    {
        $place_id = 1;
        if (auth()->user()) {
            $place_id = auth()->user()->place->id;
        }
        return $this->morphOne(Count::class, 'contable')->where('place_id', $place_id);
    }
    function counts()
    {
        $place_id = 1;
        if (auth()->user()) {
            $place_id = auth()->user()->place->id;
        }
        return $this->morphMany(Count::class, 'contable')->where('place_id', $place_id);
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
    public function payments()
    {
        return $this->morphMany(Payment::class, 'payer');
    }
    public function getDebtAttribute()
    {
        return optional($this->invoices)->sum('rest');
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    function contact()
    {
        return $this->hasOne(Contact::class);
    }
    function transactions()
    {
        $counts = $this->counts()->pluck('id');
        $place_id = 1;
        if (auth()->user()) {
            $place_id = auth()->user()->place->id;
        }
        $place = Place::find($place_id);
        return $place->transactions()->whereIn('creditable_id', $counts)->orWhereIn('debitable_id', $counts)->orderBy('created_at', 'desc');
    }
    public function sendCatalogue()
    {
        $path=Cache::get('productCatalogue_'.env('STORE_ID'));
        
        if(!$path){
            $path="https://atriontechsd.nyc3.digitaloceanspaces.com/files2/cat%C3%A1logo/catalogo%20de%20productos.pdf";
        }
        sendWSCatalogue($this->contact->cellphone, $path);
    }
}
