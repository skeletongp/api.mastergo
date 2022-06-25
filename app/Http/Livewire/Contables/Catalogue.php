<?php

namespace App\Http\Livewire\Contables;

use App\Models\CountMain;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class Catalogue extends LivewireDatatable
{
    public $padding="px-2 py-0 border-b border-blue-200 h-full relative";
    public $headTitle="";
    public $perPage=15;
    public function builder()
    {
        $this->headTitle="Catálogo de Cuentas";
        $controls=CountMain::with('counts');
        return $controls;
    }

    public function columns()
    {
        $controls=$this->builder()->get()->toArray();
        return [
            Column::callback(['code','id'], function($name, $id) use ($controls){
                $result=arrayFind($controls, 'id', $id);
                return "<div class='px-2 py-1 h-full font-medium '>${result['code']} - ${result['name']} </div>";
            })->label('Cta. Control')->searchable(),
            Column::callback(['name','id'], function($code, $id) use ($controls){
                $result=arrayFind($controls, 'id', $id);
                return view('pages.contables.rows.ctaname', ['counts'=>$result['counts']]);
            })->label('Cta. Detalle'),
            Column::callback(['created_at','id'], function($created, $id) use ($controls){
                $result=arrayFind($controls, 'id', $id);
                return view('pages.contables.rows.ctaorigen', ['counts'=>$result['counts']]);
            })->label('Origen'),
            Column::callback(['updated_at','id'], function($updated, $id) use ($controls){
                $result=arrayFind($controls, 'id', $id);
                return view('pages.contables.rows.ctatype', ['counts'=>$result['counts']]);
            })->label('Tipo'),
            Column::callback(['deleted_at','id'], function($updated, $id) use ($controls){
                $result=arrayFind($controls, 'id', $id);
                return view('pages.contables.rows.ctabalance', ['counts'=>$result['counts']]);
            })->label('Balance'),
            Column::callback(['id'], function($id) use ($controls){
                $result=arrayFind($controls, 'id', $id);
                return view('pages.contables.rows.ctadelete', ['counts'=>$result['counts']]);
            })->label('Borrar')->contentAlignCenter(),
          
        ];
    }
    public function cellClasses($row, $column)
    {
        return
            'whitespace-nowrap overflow-hidden overflow-ellipsis  px-0';
    }
}