<?php

namespace App\Http\Livewire\Procesos;

use App\Models\Formula;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class FormulaTable extends LivewireDatatable
{
    public $proceso;
    public $padding = 'p-2';
    public $headTitle = "Recursos requeridos ";
    public function builder()
    {
        $formulas = $this->proceso->formulas()->with('formulable', 'unit', 'brand', 'place', 'user');
        return $formulas;
    }

    public function columns()
    {
        $formulas = $this->builder()->get()->toArray();
        return [
            Column::callback(['formulable_id', 'id'], function ($formulable, $id) use ($formulas) {
                $result = arrayFind($formulas, 'id', $id);
                return $result['formulable']['name'];
            })->label('Recurso'),
            Column::callback(['cant', 'id'], function ($formulable, $id) use ($formulas) {
                $result = arrayFind($formulas, 'id', $id);
                return $result['cant'];
            })->label('Cant.'),
            Column::callback(['unit_id', 'id'], function ($formulable, $id) use ($formulas) {
                $result = arrayFind($formulas, 'id', $id);
                if ($result['formulable_type'] == 'App\Models\Recurso') {
                    return $result['brand']['name'];
                } else {
                    return $result['unit']['name'];
                };
            })->label('Atrib.'),
            Column::delete()->label('Eliminar'),
        ];
    }
    public function delete($id)
    {
        $formula = Formula::find($id);
        $formula->delete();
        $this->emit('refreshLivewireDatatable');
    }
}
