<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes, Prunable;

    protected $guarded = [];

    public function debe()
    {
        return $this->belongsTo(Count::class, 'debitable_id');
    }
    public function haber()
    {
        return $this->belongsTo(Count::class, 'creditable_id');
    }
    public function prunable()
    {
        $month = date('m');
        $models = Transaction::whereMonth('day', '!=', $month)->with('haber', 'debe');
        foreach ($models->get() as $model) {
            ajustCount($model);
        }
        $models->delete();

        return static::whereMonth('day', '>', $month);
    }
    
}
