<?php

namespace App\Http\Livewire\Recursos;

use App\Models\Brand;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RecursoBrand extends LivewireDatatable
{
    use AuthorizesRequests;
    public $headTitle="<span>Atributos del recurso</span>";
    public $padding="px-2";
    public $hidePagination=true;
    public $recurso;
    public function builder()
    {
        $brands=$this->recurso->brands()->with('unit');
        return $brands;
    }

    public function columns()
    {
        return [
            Column::name('name')->label('Atributo')->editable(),
            Column::name('cant')->label('Cant.'),
            Column::name('cost')->label('Costo')->editable(),
            Column::delete()->label('')
        ];
    }
    public function delete($id)
    {
        $this->authorize('Borrar Recursos');
        Brand::find($id)->delete();
        $this->emit('showAlert', 'Atributo borrado exitosamente', 'success');
        $this->emit('refreshLivewireDatatable');
    }
    public function edited($value, $key, $column, $rowId)
    {
        $this->authorize('Editar Recursos');
        DB::table(Str::before($key, '.'))
            ->where(Str::after($key, '.'), $rowId)
            ->update([$column => $value]);
        $this->emit('showAlert','Condimento editado exitosamente','success');
        $this->emit('fieldEdited', $rowId);
    }
}