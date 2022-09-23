<?php

namespace App\Http\Livewire\Recursos;

use App\Http\Classes\Column;use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ProvisionRecurso extends LivewireDatatable
{
    public $recurso;
    public $padding="px-2 white-space-nowrap";
    public function builder()
    {
        $provisions=$this->recurso->provisions()->with('atribuible','provider','user');
        return $provisions;
    }

    public function columns()
    {
        $provisions=$this->builder()->get()->toArray();
        return [
            DateColumn::name('created_at')->label('Fecha'),
            Column::callback('cant', function($cant){
                return formatNumber($cant);
            })->label('Cant.'),
          
            Column::name('cost')->label('Costo')->editable(),
            Column::callback(['prov_name','id'], function($prov, $id) use ($provisions) {
                if($prov){
                    return $prov;
                }
                $result=arrayFind($provisions, 'id', $id);
                return $result['provider']['fullname'];
            })->label('Proveedor'),
            Column::callback(['user_id','id'], function($user, $id) use ($provisions) {
               
                $result=arrayFind($provisions, 'id', $id);
                return $result['user']['fullname'];
            })->label('Responsable'),

        ];
    }
}