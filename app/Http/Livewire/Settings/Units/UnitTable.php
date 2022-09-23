<?php

namespace App\Http\Livewire\Settings\Units;

use App\Models\Unit;
use App\Http\Classes\Column;use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class UnitTable extends LivewireDatatable
{
    public function builder()
    {
        return auth()->user()->store->units()->whereNull('deleted_at');
    }

    public function columns()
    {
        return[
            Column::name('name')->label('Nombre')->searchable()->editable(),
            Column::name('symbol')->label('Símbolo')->searchable()->editable(),
            Column::delete()->label('Eliminar'),
        ];
    }
    public function delete($id)
    {
        $unit=Unit::where('id', $id)->firstOrFail();
        if ($unit->id!==1) {
            $unit->delete();
            $this->emit('refreshLivewireDatatable');
        } else {
           $this->emit('showAlert','Esta medida no puede ser borrada', 'warning');
           $this->emit('refreshLivewireDatatable');
        }
        
       
    }
}