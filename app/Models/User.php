<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
        self::creating(function ($model) {
            $model->uid = (string) Uuid::uuid4();
        });
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
    public function fullname(): Attribute
    {
        return new Attribute(
            get: fn () => $this->name . ' ' . $this->lastname
        );
    }
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
            get: fn () => $this->image?$this->image->path:env('NO_IMAGE')
        );
    }

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_users');
    }

   
    public function getStoreAttribute()
    {
        $store=$this->stores()->where('stores.id', session('store_id'))->first();
        if (!is_null(session('store_id')) && $store) {
            return $store;
        }
        return $this->stores->first();
    }
    public function getPlacesAttribute()
    {
        return $this->store->places;
    }

    public function getPlaceAttribute()
    {
        $place=$this->store->places()->where('id', session('place_id'))->first();
        if (!is_null(session('place_id')) && $place) {
            return $place;
        }
        $place=$this->store->places()->where('id', $this->place_id)->first();
        return $place;
    }
    
   
}
