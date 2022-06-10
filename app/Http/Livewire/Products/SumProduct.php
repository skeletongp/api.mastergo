<?php

namespace App\Http\Livewire\Products;

use App\Models\Product;
use App\Models\Provision;
use App\Models\Unit;
use Livewire\Component;

class SumProduct extends Component
{
    public $products, $units, $form, $productAdded = [], $provider_id, $counts, $count_code;
    public $setCost = false;

    protected $listeners = ['getUnits'];
    protected $queryString = ['productAdded'];
    public function mount()
    {
        $this->products = auth()->user()->place->products->pluck('name', 'id');
        $this->units = auth()->user()->place->units->pluck('name', 'id');
        $place = auth()->user()->place;
        $this->counts = $place->counts()->where('code','like', '100%')->pluck('name','code');
    }

    protected $rules1 = [
        'form.product_id' => 'required|exists:products,id',
        'form.cant' => 'required|numeric|min:1',
        'form.unit' => 'required|numeric|exists:units,id',
    ];
    protected $rules = [
        'productAdded' => 'required|min:1',
        'provider_id' => 'required',
    ];

    public function render()
    {
        return view('livewire.products.sum-product');
    }
    public function updatedFormProductId()
    {
        $this->emit('getUnits');
    }
    public function addProduct()
    {
        $this->validate($this->rules1);
        $this->form['product_name'] = $this->products[$this->form['product_id']];
        $exist = in_array([$this->form['product_id'], $this->form['unit']], [
            array_column($this->productAdded, 'product_id'),
            array_column($this->productAdded, 'unit')
        ]);
        if (!$exist) {
            $this->form['id'] = count($this->productAdded);
            array_push($this->productAdded, $this->form);
        } else {
            $this->emit('showAlert', 'El producto ya ha sido añadido', 'warning');
        }
        $this->reset('form');
    }
    public function remove($id)
    {
        unset($this->productAdded[$id]);
        $this->render();
    }

    public function sumCant()
    {   
        if ($this->setCost) {
            $this->rules=array_merge($this->rules, ['count_code'=>'required']);
        }
        $this->validate();
        $amount = 0;
        $code = Provision::LETTER[rand(0, 25)] . date('His');
        foreach ($this->productAdded as $added) {
            $unit = auth()->user()->place->units()
                ->where('product_id', $added['product_id'])
                ->where('unit_id', $added['unit'])->first();
            $product = Product::find($added['product_id']);
            $unit->pivot->stock = $unit->stock + $added['cant'];
            $unit->pivot->save();
            $amount += $added['cant'] * $unit->cost;
            $this->createProvision($product, $added['cant'], $code, $added['unit']);
        }
        if ($this->setCost) {
            setOutcome($amount, 'Ingreso de productos a inventario', 'N/A');
            $place = auth()->user()->place;
            $debitable = $place->counts()->where('code', '500-01')->first();
            $creditable = $place->counts()->where('code', $this->count_code)->first();
            setTransaction('Compra de mercancía',$code, $amount, $debitable, $creditable);
        }

        $this->reset('form', 'productAdded');
        $this->emit('showAlert', 'Productos añadidos al stock', 'success');
    }
    public function getUnits()
    {
        $prod = Product::find($this->form['product_id']);
        if ($prod) {
            $this->units = $prod->units()->distinct('name')->pluck('name', 'units.id');
            $this->render();
        }
    }
    public function createProvision($product, $cant, $code, $unit_id)
    {
        $product->provisions()->create([
            'code' => $code,
            'cant' => $cant,
            'atribuible_type' => Unit::class,
            'atribuible_id' => $unit_id,
            'provider_id' => $this->provider_id,
        ]);
    }
}
