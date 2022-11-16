<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Outcome extends Model
{
    use HasFactory, SoftDeletes;
    protected $connection = "mysql";

    protected $guarded = [];

    const TYPES = [
        '1' => 'Gasto de Personal',
        "2" => " Gastos por trabajos, suministros y servicios",
        "3" => " Arrendamientos",
        "4" => " Gastos de activos fijos",
        "5" => " Gastos de representación",
        "6" => " Otras deducciones admitidas",
        "7" => " Gastos financieros",
        "8" => " Gastos extraordinarios",
        "9" => " Compras y gastos que formarán parte del costo de venta",
        "10" => " Adquisiciones de activos",
        "11" => " Gastos de seguros",
    ];

    public function outcomeable()
    {
        return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function payment()
    {
        return $this->morphOne(Payment::class, 'payable');
    }
    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }
}
