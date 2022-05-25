<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Count extends Model
{
    use HasFactory;
    
    protected $fillable=[
        'code','name','balance','count_main_id','place_id'
    ];


  
    public function contable()
    {
        return $this->morphTo('contable');
    }
    public function haber()
    {
        return $this->hasMany(Transaction::class,  'creditable_id');
    }
    public function debe()
    {
        return $this->hasMany(Transaction::class,  'debitable_id');
    }
    public function scopeCant(Builder $query)
    {
        return $query->where('place_id',session('place_id')?:1)->count();
    }
}
