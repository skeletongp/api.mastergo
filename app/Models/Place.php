<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Place extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable=[
        'name','phone','uid','user_id','store_id'
    ];
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uid = (string) Uuid::uuid4();
        });
       
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_place_units')->distinct('product_id');
    }
    public function units()
    {
        return $this->belongsToMany(Unit::class, 'product_place_units')->withPivot('price_menor','price_mayor','min','stock','cost','id');
    }
    public function contable()
    {
        return $this->morphMany(Count::class, 'contable');
    }
    public function recursos()
    {
        return $this->hasMany(Recurso::class);
    }
    public function procesos()
    {
        return $this->hasMany(Proceso::class);
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
    public function incomes()
    {
        return $this->hasMany(Income::class);
    }
    public function counts()
    {
        return $this->hasMany(Count::class);
    }
    
    public function details()
    {
        return $this->hasMany(Detail::class);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function outcomes()
    {
        return $this->hasMany(Outcome::class);
    }
    public function cash()
    {
        return $this->morphOne(Count::class, 'contable')
        ->where('code','100-01')->first();
    }
  
    public function check()
    {
        return $this->morphOne(Count::class, 'contable')
        ->where('code','100-02')->first();
    }
    public function inventario()
    {
        return $this->morphOne(Count::class, 'contable')
        ->where('code','104-01')->first();
    }
    public function other()
    {
        return $this->morphOne(Count::class, 'contable')
        ->where('code','100-03')->first();
    }
    public function preference()
    {
        return $this->hasOne(Preference::class);
    }
    public function cuadres()
    {
        return $this->hasMany(Cuadre::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function findCount($code)
    {
        return $this->morphOne(Count::class, 'contable')
        ->where('code',$code)->first();
    }
}
