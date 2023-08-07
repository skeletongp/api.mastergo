<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute ;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provision extends Model
{
    use HasFactory;
    protected $connection = "mysql";
    protected $guarded = [];
    const LETTER = ['A', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
    protected $appends=['total'];

    static function boot(){
        parent::boot();
        static::creating(function ($model) {
            $model->created_by =1;
            $model->updated_by = 1;
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->user()->id;
        });
    }
    public static function code(): string
    {
        $place_id = 1;
        if (auth()->user()) {
            $place_id = auth()->user()->place->id;
        }
        $place = Place::whereId($place_id)->with('provisions')->first();
        $cant = $place->provisions()->groupBy('code')->get()->count() + 1;
        $code = str_pad($cant, 6, '0', STR_PAD_LEFT);
        return 'C' . $code;
    }


    public function provisionable()
    {
        return $this->morphTo();
    }
    public function atribuible()
    {
        return $this->morphTo();
    }
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
    public function place()
    {
        return $this->belongsTo(Place::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function total(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->cant * $this->cost;
            }
        );
    }
}