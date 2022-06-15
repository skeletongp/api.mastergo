<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Count extends Model
{
    use HasFactory, SoftDeletes;
protected $connection="mysql";
    protected $guarded=[
     
    ];
    const ORIGINS=[
        'credit'=>'Acreedor',
        'debit'=>'Deudor'
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
