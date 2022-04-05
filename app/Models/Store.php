<?php

namespace App\Models;

use App\Traits\StoreTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Ramsey\Uuid\Uuid;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class Store extends Model
{
    use HasFactory, SoftDeletes, SearchableTrait, HasRoles, StoreTrait;

    protected $fillable = [
        'uid', 'name', 'address', 'email', 'phone', 'logo', 'expire_at', 'rnc'
    ];
    protected $searchable = [
        
        'columns' => [
            'name' => 10,
            'address' => 10,
            'email' => 2,
            'rnc' => 2,
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
            $model->expires_at=Carbon::parse(now())->addMonth();
        });
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'store_users');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class,'store_roles');
    }
    public function image()
    {
       return $this->morphOne(Image::class, 'imageable');
    }
    public function logo(): Attribute
    {
        return new Attribute(
            get: fn () => $this->image?$this->image->path:env('NO_IMAGE')
        );
    }
    public function places()
    {
        return $this->hasMany(Place::class);
    }
    public function clients()
    {
        return $this->hasMany(Client::class);
    }
    public function units()
    {
        return $this->hasMany(Unit::class);
    }
    public function taxes()
    {
        return $this->hasMany(Tax::class);
    }
    public function comprobantes()
    {
        return $this->hasMany(Comprobante::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function scope()
    {
        return $this->morphToMany(Scope::class, 'scopeable','model_has_scopes');
    }
}
