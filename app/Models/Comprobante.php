<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comprobante extends Model
{
    use HasFactory, SoftDeletes;
    protected $connection = "mysql";
    protected $guarded = [];

    const MOTIVOS = [
        1 => 'Deterioro de factura impresa',
        2 => 'Errores de impresión (factura preimpresa)',
        3 => 'Impresión defectuosa',
        4 => 'Corrección de la información',
        5 => 'Cambio de productos',
        6 => 'Devolución de productos',
        7 => 'Omisión de productos',
        8 => 'Errores en secuencia de NCF',
        9 => 'Por cese de operaciones',
        10 => 'Pérdida o hurto de talonario',
    ];


    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->ncf = $model->prefix . $model->number;
        });
    }
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function place()
    {
        return $this->belongsTo(Place::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function creditnote()
    {
        return $this->hasOne(Creditnote::class);
    }
}
