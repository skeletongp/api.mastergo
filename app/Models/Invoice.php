<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    const TYPES = [
        'DOCUMENTO DE CONDUCE' => 'B00',
        'COMPROBANTE DE CRÉDITO FISCAL' => 'B01',
        'COMPROBANTE DE CONSUMIDOR FINAL' => 'B02',
        'COMPROBANTE DE RÉGIMEN ESPECIAL' => 'B14',
        'COMPROBANTE GUBERNAMENTAL' => 'B15'
    ];
}
