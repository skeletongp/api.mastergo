<?php

namespace App\Http\Livewire\Procesos;

use App\Models\Proceso;
use Livewire\Component;
use Livewire\WithPagination;

class ProcesoShow extends Component
{
    use WithPagination;
    public Proceso $proceso;
    public $productos=[];

   
    public function mount()
    {
        $this->proceso=$this->proceso->load('products');
       
    }
   
    public function render()
    {
        $procesos=auth()->user()->place->procesos()->paginate(7);
        return view('livewire.procesos.proceso-show', compact('procesos'));
    }
    public function setProcess( $proceso)
    {
        return redirect()->route('procesos.show', $proceso);
    }
    public function setObtained($product)
    {
       $prod=$this->proceso->products()->with('units')->find($product);
       $product=$prod->pivot;
       $cant=$this->productos[$product->product_id];
       $product->obtained=$cant+$product->obtained;
       $product->eficiency=($product->obtained/ $product->due)*100;
       $this->sumProduct($prod, $product->unit_id, $cant);
       $product->save();
       $this->reset('productos');
       $this->emit('showAlert', 'Monto actualizado correctamente','success');
       $this->finishProceso();
       $this->render();
    }
    public function sumProduct($product, $unit_id, $cant)
    {
       $pivot=$product->units()->where('units.id',$unit_id)->first()->pivot;
       $pivot->stock=$pivot->stock+$cant;
       $pivot->save();
    }
    public function finishProceso()
    {
        $proceso=$this->proceso;
        if ($proceso->eficiency>=100) {
           $proceso->status='Procesado';
           $proceso->save();
        } elseif ($proceso->eficiency>=1){
            $proceso->status='En Proceso';
            $proceso->save();
        }
    }
}
