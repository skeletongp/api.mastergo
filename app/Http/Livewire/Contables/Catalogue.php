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
            Column::callback('count_mains.code', function($code) {
                return view('components.view',['url'=>route('contables.countview',['code'=>$code])]);
            })->label('Detalle')
          
          
        ];
    }
   
  
    
}