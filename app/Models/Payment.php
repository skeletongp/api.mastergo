<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletingScope;
class Payment extends Model
{
    use HasFactory, SoftDeletes;
protected $connection="mysql";
    protected $guarded=[];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->place_id = auth()->user()->place->id;
            $model->day = date('Y-m-d');
        });
       
    }

    public function payable()
    {
       return  $this->morphTo()->withoutGlobalScope(SoftDeletingScope::class);
    }
    public function payer()
    {
       return  $this->morphTo()->withoutGlobalScope(SoftDeletingScope::class);
    }
    public function contable()
    {
       return  $this->morphTo();
    }
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function pdf()
    {
        return $this->morphOne(Filepdf::class, 'fileable');
    }
    public function place()
    {
        return $this->belongsTo(Place::class);
    }
}
