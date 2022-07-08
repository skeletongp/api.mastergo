<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductProduction extends Model
{
    use HasFactory;
protected $connection="mysql";
    protected $guarded=[];


   /*  public static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            $production=Production::find($model->production_id);
            $production->update([
                'cost_recursos'=>$production->costUnit,
            ]);
        });
    }
 */
    public function productible()
    {
        return $this->morphTo();
    }
    public function unitable()
    {
        return $this->morphTo();
    }
    public function production()
    {
        return $this->belongsTo(Production::class);
    }
}
