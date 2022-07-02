<?php

namespace App\Http\Livewire\Productions;

use App\Http\Traits\Livewire\Confirm;
use App\Models\Brand;
use Livewire\Component;

class CreateProduction extends Component
{
    use Confirm;
    public $proceso;
    public $form, $unit_id;
    protected $listeners=['validateAuthorization','storeProduction'];
    protected $rules=[
        'form'=>'required',
        'unit_id'=>'required|min:1',
        'form.start_at'=>'required',
    ];
    public function render()
    {
        $units=auth()->user()->place->units()->pluck('name','units.id');
        if (count($units->toArray())) {
           $this->unit_id=array_key_first($units->toArray());
        }
        return view('livewire.productions.create-production', get_defined_vars());
    }
    public function storeProduction()
    {
        $this->validate();
        if ($this->proceso->productions()->where('status','!=', 'Completado')->count()) {
            $this->emit('showAlert','Ya hay una producción pendiente','warning');
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
    function addRecursos($production, $formula){
        if ($formula->formulable_type == 'App\Models\Recurso') {
            $production->recursos()->attach($formula->formulable_id,
            [
                'cant'=>$formula->cant*$production->setted,
                'brand_id'=>$formula->brand_id,
                
            ]);
            $this->reduceBrand($formula->brand_id, $formula->cant*$production->setted);
        } else {
            $production->condiments()->attach($formula->formulable_id,
            [
                'cant'=>$formula->cant*$production->setted,
                'unit_id'=>$formula->unit_id,
                'cost'=>$formula->formulable->cost,
                'total'=>$formula->formulable->cost*$formula->cant*$production->setted
            ]);
            $this->reduceFormulable($formula->formulable, $formula->cant*$production->setted);
        }
        
    }
    function reduceBrand($brand_id, $cant)
    {
        $brand=Brand::find($brand_id);
        $brand->cant-=$cant;
        $brand->save();
    }

    function reduceFormulable($formulable, $cant)
    {
        dd($formulable);
        $formulable->cant-=$cant;
        $formulable->save();
    }
}
