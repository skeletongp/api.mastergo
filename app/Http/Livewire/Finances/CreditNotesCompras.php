<?php

namespace App\Http\Livewire\Finances;

use App\Models\Credit;
use App\Http\Classes\NumberColumn;
use App\Models\Outcome;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class CreditNotesCompras extends LivewireDatatable
{
    public $headTitle='Notas de Crédito de Compra';
    public $padding = "px-2";
    public $hideable='select';
    public function builder()
    {
        $creditnotes=Credit::where('credits.place_id', getPlace()->id)
            ->join('outcomes', 'outcomes.id', '=', 'credits.creditable_id')
            ->join('providers', 'providers.id','=','outcomes.outcomeable_id')
            ->select('credits.*', 'providers.name', 'outcomes.concepto as concepto', 'outcomes.ncf')
            ->where('credits.creditable_type', Outcome::class)
            ->orderBy('credits.id', 'desc');
            return $creditnotes;
    }

    public function columns()
    {
        return [
            Column::callback('providers.name', function($provider){
                return ellipsis($provider, 25);
            })->label('Suplidor')->searchable(),
            Column::name('outcomes.concepto')->label('Concepto'),
            Column::name('outcomes.ncf')->label('NCF Compra')->searchable(),
            Column::name('credits.ncf')->label('NCF NC')->searchable(),
            NumberColumn::name('credits.amount')->label('Monto')->formatear('money'),
            NumberColumn::name('credits.itbis')->label('ITBIS')->formatear('money'),
            DateColumn::name('credits.modified_at')->label('Fecha'),
            Column::name('comment')->label('Comentario')->searchable()->hide(),

        ];
    }
}
