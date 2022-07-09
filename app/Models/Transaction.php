<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Transaction extends Model
{
    use HasFactory, SoftDeletes, Prunable;
    protected $connection="mysql";
    protected $guarded = [];


    public static function boot(){
        parent::boot();
        static::creating(function($model){
            $model->currency_rate=Cache::get('currency')?:1;
        });
    }

    public function debe()
    {
        return $this->belongsTo(Count::class, 'debitable_id');
    }
    public function haber()
    {
        return $this->belongsTo(Count::class, 'creditable_id');
    }
    public function prunable()
    {
        $month = date('m');
        $models = Transaction::whereMonth('day', '!=', $month)->with('haber', 'debe');
        foreach ($models->get() as $model) {
            ajustCount($model);
        }
        $models->delete();

        return static::whereMonth('day', '>', $month);
    }
    public function getIncomeAttribute($value)
    {
        $debitable=$this->debe;
        if($debitable->currency=='USD'){
            return $value*$this->attributes['currency_rate'];
        }
        return $value;
    }
    public function getOutcomeAttribute($value)
    {
        $creditable=$this->haber;
        if($creditable->currency=='USD'){
            return $value*$this->attributes['currency_rate'];
        }
        return $value;
    }
}
