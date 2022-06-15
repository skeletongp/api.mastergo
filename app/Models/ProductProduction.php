<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductProduction extends Model
{
    use HasFactory;
protected $connection="mysql";
    protected $guarded=[];

    public function productible()
    {
        return $this->morphTo();
    }
    public function unitable()
    {
        return $this->morphTo();
    }
    public function production()
    {
        return $this->belongsTo(Production::class);
    }
}
