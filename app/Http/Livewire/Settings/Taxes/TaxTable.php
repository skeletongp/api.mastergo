<?php

namespace App\Http\Livewire\Settings\Taxes;

use App\Models\Tax;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class TaxTable extends LivewireDatatable
{
    public function builder()
    {
        return auth()->user()->store->taxes()->whereNull('deleted_at');
    }

    public function columns()
    {
        return[
            Column::name('name')->label('Nombre')->searchable()->editable(),
            Column::name('rate')->label('Tasa')->searchable()->editable(),
            Column::delete()->label('Eliminar'),
        ];
    }
    public function delete($id)
    {
        $tax=Tax::where('id', $id)->firstOrFail();
        if ($tax->name!=='ITBIS') {
            $tax->delete();
            $this->emit('refreshLivewireDatatable');
        } else {
            $this->emit('refreshLivewireDatatable');
           $this->emit('showAlert','Este impuesto no puede ser borrado', 'warning');
        }
        
       
    }
}