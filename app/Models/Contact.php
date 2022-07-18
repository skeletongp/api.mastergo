<?php

namespace App\Models;

use App\Observers\ClientObserver;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection="mysql";
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->fullname = $model->name . ' ' . $model->lastname;
        });
        self::updating(function ($model) {
            $model->fullname = $model->name . ' ' . $model->lastname;
        });
    }
    public function name(): Attribute
    {
        return new Attribute(
            set: fn ($value) => $this->attributes['name'] = ucwords($value, ' '),
        );
    }
    public function lastname(): Attribute
    {
        return new Attribute(
            set: fn ($value) => $this->attributes['name'] = ucwords($value, ' '),
        );
    }
}
