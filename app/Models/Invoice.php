<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded=[

    ];
    public static function boot()
    {
        $store=auth()->user()->store;
        $place=auth()->user()->place;
        parent::boot();
        self::creating(function ($model) use ($place) {
            $model->uid = (string) Uuid::uuid4();
            $model->number=$place->id.'-'.str_pad($place->invoices()->count()+1,7,'0',STR_PAD_LEFT);
        });
    }
    const TYPES = [
        'DOCUMENTO DE CONDUCE' => 'B00',
        'COMPROBANTE DE CRÉDITO FISCAL' => 'B01',
        'COMPROBANTE DE CONSUMIDOR FINAL' => 'B02',
        'COMPROBANTE DE RÉGIMEN ESPECIAL' => 'B14',
        'COMPROBANTE GUBERNAMENTAL' => 'B15'
    ];

    public function details()
    {
        return $this->morphMany(Detail::class, 'detailable');
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function client()
    {
        return $this->belongsTo(User::class);
    }
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
