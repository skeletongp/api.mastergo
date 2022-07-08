<?php

namespace App\Http\Livewire\Productions;

use App\Http\Traits\Livewire\Confirm;
use App\Models\Brand;
use Livewire\Component;

class CreateProduction extends Component
{
    use Confirm;
    public $proceso, $units;
    public $form, $unit_id;
    protected $listeners=['validateAuthorization','storeProduction'];
    protected $rules=[
        'form'=>'required',
        'form.setted'=>'required',
        'form.expected'=>'required',
        'unit_id'=>'required|min:1',
        'form.start_at'=>'required',
    ];
    function mount($proceso)
    {
        $this->units=auth()->user()->place->units()->pluck('name','units.id');
        $this->proceso=$proceso;
        $this->unit_id=$proceso->unit_id;
    }
    public function render()
    {
        return view('livewire.productions.create-production', get_defined_vars());
    }
    public function storeProduction()
    {
        $this->validate();
        if ($this->proceso->productions()->where('status','!=', 'Completado')->count()) {
            $this->emit('showAlert','Ya hay una producción pendiente','warning');
            return ;
        } 
        if(!$this->validateRecursos()){
            return ;
        }
        if(! $this->validateCondiments()){
            return ;
        }
        $this->form['user_id']=auth()->user()->id;
        $this->form['unit_id']=$this->unit_id;
        $production=$this->proceso->productions()->create($this->form);
        foreach ($this->proceso->formulas as $formula) {
           $this->addRecursos($production, $formula);
        }

        $this->reset('form');
        $this->emit('refreshLivewireDatatable');
    }
    function validateCondiments(){
        if ($this->proceso->condiments->count()) {
            foreach($this->proceso->condiments as $cond){
                if ($cond->pivot->cant*$this->form['setted']>$cond->cant){
                    $this->emit('showAlert','No hay suficiente cantidad de '.$cond->name.' para la producción','warning', 5000);
                    return false;
                }
            }
        }  return true;
    }
    function validateRecursos(){
        if($this->proceso->recursos->count()){
            foreach($this->proceso->recursos as $recurso){
                $brand=Brand::find($recurso->pivot->brand_id);
                if ($recurso->pivot->cant*$this->form['setted']>$brand->cant){
                    $this->emit('showAlert','No hay suficiente cantidad de '.$recurso->name.' para la producción','warning', 5000);
                    return false;
                }
            }
        } 
            return true;
    }
    function addRecursos($production, $formula){
        if ($formula->formulable_type == 'App\Models\Recurso') {
            $production->recursos()->attach($formula->formulable_id,
            [
                'cant'=>$formula->cant*$production->setted,
                'brand_id'=>$formula->brand_id,
                
            ]);
                $brand=Brand::find($formula->brand_id);
                $production->update([
                    'cost_recursos'=>$production->cost_recursos+($formula->cant*$production->setted*$brand->cost),
                ]);
            $this->reduceBrand($formula->brand_id, $formula->cant*$production->setted, $formula->formulable, $brand);
        } else {
            $total=$formula->formulable->cost*$formula->cant*$production->setted;
            $production->condiments()->attach($formula->formulable_id,
            [
                'cant'=>$formula->cant*$production->setted,
                'unit_id'=>$formula->unit_id,
                'cost'=>$formula->formulable->cost,
                'total'=>$total
            ]);
            $this->reduceFormulable($formula->formulable, $formula->cant*$production->setted);
            $production->update([
                'cost_condiment'=>$production->cost_condiment+$total
            ]);
        }
        
    }
    function reduceBrand($brand_id, $cant, $formulable, $brand)
    {
        
        $brand->cant-=$cant;
        $place=auth()->user()->place;
        $creditable=$place->findCount('104-02');
        if ($formulable->contable()->first()){
            $creditable=$formulable->contable()->first();
        }
        $debitable=$place->findCount('104-04');
        setTransaction('MP a Producto en Proceso',date('Ymdh'), $cant*$brand->cost, $debitable, $creditable, 'Crear Producciones');
        $brand->save();
    }

    function reduceFormulable($formulable, $cant)
    {
        $formulable->cant-=$cant;
        $formulable->save();
    }
}
