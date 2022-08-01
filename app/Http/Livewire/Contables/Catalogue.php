<?php

namespace App\Http\Livewire\Contables;

use App\Models\CountMain;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class Catalogue extends LivewireDatatable
{
    public $padding="px-2";
    public $headTitle="Catálogo de Cuentas";
    public $perPage=5;
    public $code=101;
    public function builder()
    {
        $controls=CountMain::orderBy('count_mains.code')
        ->leftjoin('counts','counts.count_main_id','=','count_mains.id')
        ->groupby('count_mains.id')
        ;
        return $controls;
    }

    public function columns()
    {
        return [
            Column::callback(['code','name'], function($code, $name) {
                return "<div class=' py-1 h-full font-medium '>${code} - ${name} </div>";
            })->label('Cta. Control')->searchable(),
            Column::callback(['counts.id:count'], function($count) {
                return $count." Cuentas";
            })->label('Ctas. Detalle'),
            Column::callback('counts.origin', function($origin) {
                return 'En desarrollo';
                return $origin;
            })->label('Origen')
          /*   Column::callback(['created_at','id'], function($created, $id) use ($controls){
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
            })->label('Balance'), */
          /*   Column::callback(['id'], function($id) use ($controls){
                $result=arrayFind($controls, 'id', $id);
                return view('pages.contables.rows.ctadelete', ['counts'=>$result['counts']]);
            })->label('Borrar')->contentAlignCenter(), */
          
        ];
    }
   
  
    
}