<?php

namespace App\Models;

use App\Events\UserEvent;
use App\Observers\UserObserver;
use App\Traits\NotRoleTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements Searchable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;
    protected $connection = "moso_master";

    public function getSearchResult(): SearchResult
    {
        $url = route('users.index', $this->id);

        return new SearchResult(
            $this,
            $this->fullname,
            $url
        );
    }


    protected $guarded=[
       
    ];
   

    public function getRouteKeyName()
    {
        return 'uid';
    }

    public static function boot()
    {
        parent::boot();
        self::observe(new UserObserver);
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $fillable = [
        'name',
        'lastname',
        'email',
        'username',
        'password',
        'phone',
        'logueable',
        'avatar',
        'store_id',
        'place_id',
        'loggeable'
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function password(): Attribute
    {
        return new Attribute(
            set: fn ($value) => Hash::make($value)
        );
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
    public function name(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value
        );
    }

    public function stores()
    {
        return $this->belongsToMany(Store::class, env('DB_DATABASE') . '.store_users');
    }


    public function contable()
    {
        $place_id = 1;
        if (auth()->user()) {
            $place_id = auth()->user()->place->id;
        }
        return $this->morphOne(Count::class, 'contable')->where('place_id', $place_id);
    }


    public function getStoreAttribute()
    {
        $store = Cache::get('store_' . $this->id);
        if (!is_null($store)) {
            return $store;
        }
        $store = $this->stores()->where('stores.id', env('STORE_ID'))
            ->with(
                'clients',
                'products',
                'roles',
                'invoices',
                'providers',
                'incomes',
                'banks',
                'recursos',
                'comprobantes',
                'units',
                'places'
            )
            ->first();
     
        Cache::put('store_' . $this->id, $store);
        return $store;
    }
    public function getPlacesAttribute()
    {

        return $this->store->places;
    }

    public function getPlaceAttribute()
    {
        $place = Cache::get('place_' . $this->id);
        if (!is_null($place)) {
            return $place;
        }
        $placeOnStore = $this->store->places()->whereId($this->place_id)->first();
        if (!$placeOnStore) {
            $place = $this->store->places()->first();
            $this->update(['place_id' => $place->id]);
        } else {
            $place = $placeOnStore;
        }
        Cache::put('place_' . $this->id, $place);
        return $place;
    }


    public function payments()
    {
        return $this->morphMany(Payment::class, 'contable');
    }
}
