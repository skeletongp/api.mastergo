<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

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

    public static function boot(){
        parent::boot();
        static::updating(function($model){
            if($model->currency=='USD'){
                $model->balance_real=$model->balance/Cache::get('currency');
            } else{
                $model->balance_real=$model->balance;
            }
        });
    }
   
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
   
   public function getBalanceAttribute($value)
   {
       if($this->currency=='USD'){
        return 33;
           return $this->balance_real*Cache::get('currency');
        }
        return $value;
   }

}
