<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Proceso extends Model implements Searchable
{
    use HasFactory, SoftDeletes;
    protected $connection = "mysql";

    protected $fillable = [
        'name',
        'code',
        'start_at',
        'user_id',
        'unit_id',
        'place_id'
    ];

    public function getSearchResult(): SearchResult
    {
        $url = route('procesos.show', $this->id);

        return new SearchResult(
            $this,
            $this->name,
            $url
        );
    }
    public function recursos()
    {
        return $this->belongsToMany(Recurso::class, 'formulas', 'proceso_id', 'formulable_id')->where('formulable_type', Recurso::class)->withPivot('cant', 'brand_id');
    }
    public function condiments()
    {
        return $this->belongsToMany(Condiment::class, 'formulas', 'proceso_id', 'formulable_id')->where('formulable_type', Condiment::class)->withPivot('cant');
    }
    public function productions()
    {
        return $this->hasMany(Production::class);
    }
    function formulas()
    {
        return $this->hasMany(Formula::class);
    }
    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => ucfirst($value),
        );
    }
}
