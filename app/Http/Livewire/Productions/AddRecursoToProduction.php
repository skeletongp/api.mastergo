<?php

namespace App\Http\Livewire\Productions;

use App\Models\Brand;
use App\Models\Production;
use App\Models\Recurso;
use Livewire\Component;

class AddRecursoToProduction extends Component
{
    public $production, $recursos;
    public $recurso, $brand, $brands;
    public $cant, $recurso_id, $brand_id, $selected=[], $restar=true;
    public function render()
    {
        $this->recursos=auth()->user()->place->recursos->pluck('name','id');
        return view('livewire.productions.add-recurso-to-production');
    }
    public function addSelected()
    {
       $this->validate([
           'cant'=>'required|min:1',
           'recurso_id'=>'required',
           'brand_id'=>'required',
       ]);
       array_push($this->selected,[
        'recurso'=>$this->recurso->name,
        'recurso_id'=>$this->recurso->id,
        'brand'=>$this->brand->name,
        'brand_id'=>$this->brand->id,
        'cant'=>$this->cant
       ]);
       $this->reset('recurso_id','brand_id','cant');
    }
    public function updatedRecursoId(){
        $this->recurso=Recurso::whereId($this->recurso_id)->with('brands')->first();
        if ($this->recurso) {
            $this->brands=$this->recurso->brands;
        }
    }
    public function updatedBrandId(){
        $this->brand=Brand::whereId($this->brand_id)->first();
        
    }
    public function storeSelected()
    {
       $production=Production::whereId($this->production['id'])->with('recursos')->first();
        foreach ($this->selected as $item) {
           $production->recursos()->attach($item['recurso_id'],[
               'brand_id'=>$item['brand_id'],
               'cant'=>$item['cant'],
           ]);
           if ($this->restar) {
               $this->restBrand($item);
           }
        }
        $production->update(['status'=>'Iniciado']);
        $this->reset('recurso_id','brand_id','cant','selected');
      $this->emit('render');
      $this->emit('showAlert','Recursos añadidos existosamente','success');
    }
    public function removeRecurso($id)
    {
        unset($this->selected[$id]);
        $this->selected=array_values($this->selected);
    }
    public function restBrand($item)
    {
       $brand=Brand::whereId($item['brand_id'])->first();
        $brand->cant=$brand->cant-$item['cant'];
        $brand->save();
    }
}
