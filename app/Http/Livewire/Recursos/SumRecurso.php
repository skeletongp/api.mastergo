<?php

namespace App\Http\Livewire\Recursos;

use App\Models\Brand;
use App\Models\Recurso;
use Livewire\Component;

class SumRecurso extends Component
{
    public $recursos, $brands;
    public $recurso, $cant, $brand_id, $recurso_id;
    public $selected = [], $gasto=true;

    public function mount()
    {
        $this->recursos = auth()->user()->place->recursos()->with('brands')->orderBy('name')->get();
      
    }

    public function render()
    {
        return view('livewire.recursos.sum-recurso');
    }
    public function updatedRecursoId()
    {
        $this->reset('brands');
        $this->recurso = Recurso::find($this->recurso_id);
        if ($this->recurso) {
            $this->brands=$this->recurso->brands->pluck('name', 'id');
            $this->brand_id=null;
        }
    }

    public function addSelected()
    {
        $this->validate([
            'brand_id' => 'required',
            'cant' => 'required',
        ]);
        $brand = Brand::whereId($this->brand_id)->with('recurso')->first();
        array_push(
            $this->selected,
            [
                'recurso_id'=>$brand->recurso_id,
                'recurso'=>$brand->recurso->name,
                'brand_id'=>$brand->id,
                'brand'=>$brand->name,
                'cant'=>$this->cant,
                'cost'=>$brand->cost*$this->cant
            ]
        );
        $this->reset('brand_id', 'cant', 'recurso_id','brands');
    }
    public function storeSelected(){
        foreach ($this->selected as $selected) {
            $brand=Brand::whereId($selected['brand_id'])->with('recurso')->first();
            $brand->update([
                'cant'=>$brand->cant+$selected['cant']
            ]);
        }
        if ($this->gasto) {
            $amount=array_sum(array_column($this->selected, 'cost'));
            setOutcome($amount, 'Ingreso de recursos a inventario','N/A');
            $debitable= auth()->user()->place->counts()->where('code', '500-01')->first();;
            $creditable=auth()->user()->place->cash();
            setTransaction('Ingreso de recursos', date('d/m/Y'),$amount, $debitable, $creditable);
        }
        $this->reset('brand_id', 'cant','selected');
        $this->emit('showAlert','Stock actualizado con éxito','success');
    }
    public function removeRecurso($id)
    {
        unset($this->selected[$id]);
        $this->selected=array_values($this->selected);
    }
}
