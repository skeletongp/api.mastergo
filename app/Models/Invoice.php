<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;
protected $connection="mysql";

    protected $guarded=[

    ];
    public static function boot()
    {
        parent::boot();
        self::updated(function ($model)  {
            if ($model->total>$model->rest) {
                $model->isEditable = 0;
                $model->save();
            }
            
        });
    }
    const TYPES = [
        'COMPROBANTE DE CRÉDITO FISCAL' => 'B01',
        'COMPROBANTE DE CONSUMIDOR FINAL' => 'B02',
        'COMPROBANTE DE RÉGIMEN ESPECIAL' => 'B14',
        'COMPROBANTE GUBERNAMENTAL' => 'B15',
        'DOCUMENTO DE CONDUCE' => 'B00',
    ];

    public function details()
    {
        return $this->morphMany(Detail::class, 'detailable');
    }
    public function payment()
    {
        return $this->morphOne(Payment::class, 'payable');
    }
    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function place()
    {
        return $this->belongsTo(Place::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class)->withTrashed();
    }
    public function comprobante()
    {
        return $this->belongsTo(Comprobante::class)->withTrashed();;
    }
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id')->withTrashed();;
    }
    public function contable()
    {
        return $this->belongsTo(User::class, 'contable_id')->withTrashed();;
    }
    public function pdf()
    {
        return $this->morphOne(Filepdf::class, 'fileable');
    }
    public function getPdfLetterAttribute()
    {
        return $this->pdf->pathLetter;
    }
    public function getPdfThermalAttribute()
    {
        return $this->pdf->pathThermal;
    }
    public function incomes()
    {
        return $this->morphMany(Income::class, 'incomeable')->withTrashed();;
    }

    public function taxes()
    {
        return $this->belongsToMany(Tax::class, 'invoice_taxes')->withPivot('amount');
    }
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function cheque(): Attribute
    {
        return new Attribute(
            get: fn () => $this->image?$this->image->path:env('NO_IMAGE')
        );
    }
   
}
