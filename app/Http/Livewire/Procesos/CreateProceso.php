<?php

namespace App\Http\Livewire\Procesos;

use App\Models\Proceso;
use App\Models\Product;
use App\Models\Recurso;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class CreateProceso extends Component
{
    public $form, $recursos, $units, $products;
    public $selRecurso, $selProduct;
    public $fRecursos = [], $fProducts = [];
    public $place;
    public $recursoId, $recursoCant;
    public $productId, $productDue, $productUnit;
    use AuthorizesRequests;
    public function mount()
    {
        $this->place = auth()->user()->place;
        $this->recursos = $this->place->recursos;
        $this->products = $this->place->products;
        $this->products = $this->place->products;
    }

    protected $rules = [
        'form.name' => 'required|string|min:10|max:45',
        'form.start_at' => 'required|date',
        'fRecursos' => 'required|min:1',
        'fProducts'=>'required|min:1',
    ];

    public function render()
    {
        return view('livewire.procesos.create-proceso');
    }

    /* Aquí se crea el proceso */
    public function createProceso()
    {
        $this->authorize('Iniciar Procesos');
        $this->validate();
        $proceso = $this->place->procesos()->create([
            'name' => $this->form['name'],
            'start_at' => $this->form['start_at'],
            'user_id' => auth()->user()->id,
        ]);
        $this->createProcesoRecurso($proceso);
        $this->crearProcesoProducto($proceso);
        $this->reset('form', 'selRecurso', 'recursoId', 'recursoCant', 'fRecursos', 'fProducts');
        $this->emit('showAlert','Proceso Registrado exitosamente','success');
        $this->render();
    }
    public function updatedRecursoId()
    {
        $this->selRecurso = $this->recursos->find($this->recursoId);
        $this->units=$this->selRecurso->units;
    }
    public function updatedProductId()
    {
        $this->selProduct = $this->products->find($this->productId);
        $this->units=$this->selProduct->units;
    }
    public function addRecurso()
    {
        if (!in_array($this->recursoId, array_column($this->fRecursos, 'recurso_id'))) {
            array_push($this->fRecursos, [
                'recurso_id' => $this->recursoId,
                'cant' => $this->recursoCant,
                'name' => $this->selRecurso->name,
            ]);
        }
        $this->reset('recursoId', 'recursoCant', 'selRecurso');
    }
    public function addProduct()
    {
        $existingProduct = array_filter($this->fProducts, function($val){
            return ($val['product_id']==$this->productId and $val['unit_id']==$this->productUnit);
        });
        if (empty($existingProduct)) {
            array_push($this->fProducts, [
                'product_id' => $this->productId,
                'due' => $this->productDue,
                'unit_id' => $this->productUnit,
                'name' => $this->selProduct->name,
                'unitname' => $this->units->find($this->productUnit)->symbol,
            ]);
        } else{
            $this->getExistingProduct();
        }
        
        $this->reset('productId', 'productDue', 'productUnit', 'selProduct');
    }
    public function getExistingProduct()
    {
        $existingProduct = array_filter($this->fProducts, function($val){
            return ($val['product_id']==$this->productId && $val['unit_id']==$this->productUnit);
        });
        $this->fProducts[array_key_first($existingProduct)]['due']=$this->productDue;
    }
    public function removeRecurso($recurso)
    {
        $ind = array_search($recurso, array_column($this->fRecursos, 'recurso_id'));
        unset($this->fRecursos[$ind]);
    }
    public function removeProduct($product)
    {
        $ind = array_search($product, array_column($this->fProducts, 'product_id'));
        unset($this->fProducts[$ind]);
    }

    public function createProcesoRecurso(Proceso $proceso)
    {
        foreach ($this->fRecursos as $recurso) {
            $rec = Recurso::find($recurso['recurso_id']);
            $proceso->recursos()->save(
                $rec,
                [
                    'cant' => $recurso['cant'],
                ]
            );
            $rec->cant = $rec->cant - $recurso['cant'];
            $rec->save();
        }
    }
    public function crearProcesoProducto(Proceso $proceso)
    {
        foreach ($this->fProducts as $product) {
           $prod=Product::find($product['product_id']);
           $proceso->products()->save($prod,[
                'unit_id'=>$product['unit_id'],
                'due'=>$product['due']
           ]);

        }
    }
}
