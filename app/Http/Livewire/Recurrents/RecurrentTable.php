<?php

namespace App\Http\Livewire\Recurrents;

use App\Models\Recurrent;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class RecurrentTable extends LivewireDatatable
{
    public $headTitle="Obligaciones recurrentes";
    public $padding='px-2';
    use AuthorizesRequests;
    public function builder()
    {
        $place=auth()->user()->place;
        $recurrents=$place->recurrents()->with('count');
        return $recurrents;
    }

    public function columns()
    {
        $recurrents=$this->builder()->get()->toArray();
        return [
            Column::name('name')->label('Nombre'),
            Column::callback('amount', function($amount){
                return '$'.formatNumber($amount);
            })->label('Monto'),
            Column::name('recurrency')->label('Recurrencia'),
            Column::callback(['count_id', 'id'], function($count, $id) use ($recurrents){
               $result= arrayFind($recurrents, 'id', $id);  
                return $result['count']['name'];
            })->label('Cuenta'),   
            DateColumn::name('expires_at')->label('Vencimiento'),
            Column::callback(['created_at','id'], function($created_at, $id) use ($recurrents){
                $recurrent= arrayFind($recurrents, 'id', $id);  
                return view('pages.recurrents.actions',compact('recurrent'));
            })->label('Acciones'),
            Column::delete()->label('Eliminar'),
        ];
    }
    public function delete($id)
    {
        $this->authorize('Borrar Obligaciones');
        $recurrent=Recurrent::find($id);
        $recurrent->delete();
        $this->emit('refreshLivewireDatatable');
    }
}