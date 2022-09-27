<?php

namespace App\Http\Livewire\Contables;

use App\Http\Classes\NumberColumn;
use App\Models\CountMain;
use App\Http\Classes\Column;use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class Catalogue extends LivewireDatatable
{
    public $padding="px-2";
    public $headTitle="Catálogo de Cuentas";
    public $perPage=5;
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
            Column::callback('count_mains.code', function($code) {
                return view('components.view',['url'=>route('contables.countview',['code'=>$code])]);
            })->label('Ver'),
            Column::name('count_mains.code')->label('Código'),
            Column::name('count_mains.name')->label('Cuenta Control'),
           
            NumberColumn::name('counts.balance:SUM')->label('Balance')->formatear('money','font-bold'),
          
          
        ];
    }
   
  
    
}