<?php

namespace App\Models;

use App\Events\UserEvent;
use App\Observers\UserObserver;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Ramsey\Uuid\Uuid;
use Nicolaslopezj\Searchable\SearchableTrait;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SearchableTrait, HasRoles, SoftDeletes;

   

    protected $fillable = [
        'name',
        'lastname',
        'email',
        'username',
        'password',
        'phone',
        'avatar',
        'store_id',
        'place_id',
    ];
    protected $searchable = [
        'columns' => [
            'name' => 10,
            'lastname' => 5,
            'email' => 1,
            'username' => 3,

        ]
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
        return $this->belongsToMany(Store::class, 'store_users');
    }
    
    public function contable()
    {
        return $this->morphMany(Count::class, 'contable');
    }


    public function getStoreAttribute()
    {
        $store = Cache::get('store_' . $this->id);
        if (!is_null($store)) {
            return $store;
        }
        $store = $this->stores()->where('stores.id', $this->place_id)->with('clients','products')->first();
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
        $place = $this->store->places()->first();
        Cache::put('place_' . $this->id, $place);
        return $place;
    }
}
