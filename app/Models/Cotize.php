<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cotize extends Model
{
    use HasFactory, SoftDeletes;
    protected $connection="mysql";

    protected $guarded=[];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function details()
    {
        return $this->morphMany(Detail::class, 'detailable');
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function place()
    {
        return $this->belongsTo(Place::class);
    }
    public function pdf()
    {
        return $this->morphOne(Filepdf::class, 'fileable');
    }

}
