<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Nicolaslopezj\Searchable\SearchableTrait;
class Proceso extends Model implements Searchable
{
    use HasFactory, SoftDeletes, SearchableTrait;
protected $connection="mysql";

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
        return $this->belongsToMany(Recurso::class, 'proceso_recursos')->withPivot('cant')->withTimestamps();
    }
    public function productions(){
        return $this->hasMany(Production::class);
    }
   function formulas(){
       return $this->hasMany(Formula::class);
   }
}
