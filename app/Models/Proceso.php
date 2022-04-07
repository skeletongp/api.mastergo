<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proceso extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'start_at',
        'user_id',
        'place_id'
    ];

    public function name(): Attribute
    {
        return new Attribute(
            set: fn ($value) => $value . ' ' . date('d_m_Y H_i_s')
        );
    }

    public function recursos()
    {
        return $this->belongsToMany(Recurso::class, 'proceso_recursos')->withPivot('cant')->withTimestamps();
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'proceso_product_units')->withPivot('due', 'obtained', 'eficiency', 'unit_id')->withTimestamps();
    }
    public function units()
    {
        return $this->belongsToMany(Unit::class, 'proceso_product_units')->withPivot('due', 'obtained', 'eficiency', 'unit_id')->withTimestamps();
    }
    public function eficiency(): Attribute
    {
        $obt = array_sum($this->products->pluck('pivot.obtained')->toArray());
        $due = array_sum($this->products->pluck('pivot.due')->toArray());
        if ($obt && $due) {
            $ef = ($obt / $due)*100;
        } else {
            $ef = 0;
        }

        return new Attribute(
            get: fn () => $ef
        );
    }
}
