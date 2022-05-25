<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded=[];
    public function payable()
    {
        $this->morphTo();
    }
    public function payer()
    {
        $this->morphTo();
    }
    public function contable()
    {
        $this->morphTo();
    }
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function pdf()
    {
        return $this->morphOne(Filepdf::class, 'fileable');
    }
}
