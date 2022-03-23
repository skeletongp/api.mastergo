<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Store extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uid', 'name', 'address', 'email', 'phone', 'logo', 'expire_at'
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
    public function users()
    {
        return $this->hasMany(User::class);
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
}
