<?php

namespace App\Http\Livewire\Productions;

use App\Models\Condiment;
use App\Models\CondimentProduction;
use Illuminate\Support\Facades\DB;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Illuminate\Support\Str;

class ProductionCondiment extends LivewireDatatable
{
    public $production;
    public $headTitle = 'Condimentos utilizados';
    public $padding = 'px-2';
    public function builder()
    {
        $condiments = CondimentProduction::query()
            ->leftJoin('condiments', 'condiment_productions.condiment_id', '=', 'condiments.id')
            ->leftJoin('units', 'condiment_productions.unit_id', '=', 'units.id')
            ->where('condiment_productions.production_id', $this->production->id);
        return $condiments;
    }

    public function columns()
    {
        //$condiments=$this->builder()->get()->toArray();
        return [
            Column::index($this),
            Column::name('condiments.name')->label('Nombre')->searchable(),
            Column::name('condiment_productions.cant')->label('Cant.')->editable(),
            Column::name('units.name')->label('Unidad')->searchable(),
            Column::name('condiment_productions.cost')->label('Costo')->editable(),
            Column::name('condiment_productions.total')->label('Total')->searchable(),

        ];
    }
    function edited($value, $key, $column, $rowId)
    {
        $cnd=CondimentProduction::where('id', $rowId)->first();
        
            $cnd->update([$column => $value]);
        $this->emit('fieldEdited', $rowId);
        $this->emit('refreshLivewireDatatable');
    }
}
