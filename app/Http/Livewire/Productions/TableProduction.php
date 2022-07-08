<?php

namespace App\Http\Livewire\Productions;

use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
class TableProduction extends LivewireDatatable
{
    public $proceso, $status;
    public $headTitle;
    public $padding = 'px-2';
    public function builder()
    {
        $query= $this->proceso->productions()->orderBy('id','desc')->with('recursos','unit','brands.recurso','proceso','products.productible','products.unitable');
        if ($this->status=='Completado') {
            $this->headTitle=$this->proceso->name.' completado';
            return $query->whereStatus('Completado');
        } else {
            $this->headTitle=$this->proceso->name.' en proceso';
            return  $query->where('status','!=','Completado');
        }
        
    }

    public function columns()
    {
        $productions = $this->builder()->get()->toArray();
        return [
            Column::callback('id',function($id) use ($productions) {
                return "<a href='".route('productions.show',$id)."'><span class='fas fa-eye'> </span> </a>";
            })->label('Ver'),
            DateColumn::name('start_at')->label('Inicio')->format('d/m/y H:i'),
            Column::callback('setted', function ($setted) {
                return formatNumber($setted);
            })->label('Invertido'),
            Column::callback(['id', 'unit_id'], function ($id) use ($productions) {
                $result = arrayFind($productions, 'id', $id);
                return $result['unit']['name'];
            })->label('Unidad'),
            Column::callback('cost_recursos', function($cost){
                return '$'.formatNumber($cost);
            })->label('C. MP'),
            Column::callback('cost_condiment', function($cost){
                return '$'.formatNumber($cost);
            })->label('C. COND'),
            Column::callback('getted', function ($getted) {
                return formatNumber($getted);
            })->label('Obtenido'),

            Column::callback('eficiency', function ($eficiency) {
                return formatNumber($eficiency).'%';
            })->label('Efect.'),
            DateColumn::name('end_at')->label('Fin')->format('d/m/y H:i'),
            $this->status=='Completado'?
            Column::name('status')->label('Estado'):
            Column::callback(['id','created_at'], function($id) use ($productions){
                $production = arrayFind($productions, 'id', $id);
                return view('pages.productions.actions', get_defined_vars());
            })->label('Acciones')->contentAlignCenter()
        ];
    }
}
