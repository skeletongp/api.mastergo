<?php

namespace App\Http\Livewire\Condiments;

use App\Models\Condiment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Illuminate\Support\Str;
class TableCondiment extends LivewireDatatable
{
    use AuthorizesRequests;

    public $headTitle = "Condimentos";
    public $padding="px-2";
    public $pageName='condiments';
    protected $queryString = [
        'page' => ['except' => 1, 'as' => 'page_condiment'],
    ];
    public function builder()
    {
        $condiments = auth()->user()->place->condiments()->select()->with('unit');

        return $condiments;
    }

    public function columns()
    {
        $condiments = $this->builder()->get()->toArray();
        return [
           Column::name('code')->label('Cod.'),
            Column::name('name')->label('Nombre')->searchable()->editable(),
            Column::callback(['created_at', 'id'], function ($created, $id) use ($condiments) {
                $result = arrayFind($condiments, 'id', $id);
               
                return formatNumber($result['cant']);
            })->label('Cant'),
            Column::name('cost')->label('Costo')->editable(),
            Column::delete()->label('')
        ];

        
    }
    public function delete($id)
    {
        $this->authorize('Borrar Recursos');
        Condiment::find($id)->delete();
        $this->emit('showAlert', 'Condimento borrado exitosamente', 'success');
        $this->emit('refreshLivewireDatatable');
    }
    public function edited($value, $key, $column, $rowId)
    {
       
        $this->authorize('Editar Recursos');
        DB::table(Str::before($key, '.'))
            ->where(Str::after($key, '.'), $rowId)
            ->update([$column => $value]);
            $this->emit('fieldEdited', $rowId);
        $this->emit('showAlert','Condimento editado exitosamente','success');
    }
}