<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Invoice extends Model implements Searchable
{
    use HasFactory, SoftDeletes;
protected $connection="mysql";

    protected $guarded=[

    ];
    public static function boot()
    {
        parent::boot();
        self::updating(function ($model)  {
            if ($model->payment->total>$model->rest) {
                $model->isEditable = 0;
            }
            
        });
    }
    public function getSearchResult(): SearchResult
    {
       $url = route('invoices.show', $this->id);
    
       $client=$this->client->name?:$this->client->contact->fullname;
        return new SearchResult(
           $this,
           str_replace('0','',$this->number).' '.$client,
           $url
        );
    }
   
    const TYPES = [
        'COMPROBANTE DE CRÉDITO FISCAL' => 'B01',
        'COMPROBANTE DE CONSUMIDOR FINAL' => 'B02',
        'COMPROBANTE DE RÉGIMEN ESPECIAL' => 'B14',
        'COMPROBANTE GUBERNAMENTAL' => 'B15',
        'DOCUMENTO DE CONDUCE' => 'B00',
        'NOTA DE CRÉDITO' => 'B04',
        'NOTA DE DÉBITO' => 'B03',
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
        return optional($this->pdf)->pathLetter?:'none';
    }
    public function getPdfThermalAttribute()
    {
        return optional($this->pdf)->pathThermal?:'none';
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
    public function name(): Attribute
    {
        return new Attribute(
            set: fn ($value) => $this->attributes['name'] = ucwords($value, ' '),
        );
    }
    public function creditnote()
    {
        return $this->hasOne(Creditnote::class);
    }
   
}
