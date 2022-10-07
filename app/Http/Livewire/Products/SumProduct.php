<?php

namespace App\Http\Livewire\Products;

use App\Http\Livewire\Products\Includes\SumProductTrait;
use App\Models\Outcome;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Provision;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SumProduct extends Component
{
    use SumProductTrait;
    public $efectivoCode = '100-02', $efectivos=[];
    public $products, $providers, $units, $form=[], $productAdded = [], $provider_id, $counts, $code_name, $count_code="104-01", $ref='N/D';
    public $efectivo = 0, $tarjeta = 0, $transferencia = 0, $banks, $bank_id, $ref_bank, $tax=0, $discount=0;
    public $setCost = false, $total=0, $open=false;

    protected $listeners = ['getUnits'];
    protected $queryString = ['productAdded', 'setCost', 'total','discount'];
    public function mount()
    {
        $place = auth()->user()->place;
        $store = auth()->user()->store;
        $this->efectivoCode = '100-02';
        $this->efectivos=$place->counts()->where('code','like','100%')->pluck('name','id');
        $this->providers = $store->providers()->pluck('fullname', 'providers.id');
        $this->products = $place->products()->where('type','Producto')->pluck('name', 'products.id');
        $this->units = $place->units()->pluck('name', 'units.id');
        $this->counts = $place->counts()->where('code', 'like', '104%')->pluck('name', 'counts.code');
        $this->banks = $store->banks()->select(DB::raw('CONCAT(bank_name," ",bank_number) AS name, id'))->pluck('name', 'banks.id');
        $count=$place->counts()->where('code',$this->count_code)->first();
            $this->code_name=$count->code.'-'.ellipsis($count->name,27);
            
    }

    protected $rules1 = [
        'form.product_id' => 'required|exists:products,id',
        'form.cant' => 'required|numeric|min:1',
        'form.unit' => 'required|numeric|exists:units,id',
        'form.cost' => 'required|numeric',
        
    ];
    protected $rules = [
        'productAdded' => 'required|min:1',
        'provider_id' => 'required',
        'ref' => 'required',
    ];

    public function render()
    {
        return view('livewire.products.sum-product');
    }
    public function updatedCodeName(){
        $this->count_code=strtok($this->code_name, ' ');
    }
    public function updatedFormProductId()
    {
        $this->form['unit']=null;
        $this->render();
        $this->emit('getUnits');
    }
    public function updatedSetcost()
    {
        $this->render();
    }
    public function updatedDiscount($value)
    {
        $this->total=$this->total-$value;
        $this->render();
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
            $this->form['unit_name'] = $this->units[$this->form['unit']];
            array_push($this->productAdded, $this->form);
            $this->total+=($this->form['cant']*$this->form['cost']);
        } else {
            $this->emit('showAlert', 'El producto ya ha sido añadido', 'warning');
        }
        $this->reset('form');
    }
    public function remove($id)
    {
        $this->total-=($this->productAdded[$id]['cant']*$this->productAdded[$id]['cost']);
        unset($this->productAdded[$id]);
        $this->render();
    }

    public function sumCant()
    {
        
        $this->validate();
        
        $code = Provision::code();
        foreach ($this->productAdded as $added) {
            $unit = auth()->user()->place->units()
            ->where('product_id', $added['product_id'])
            ->where('unit_id', $added['unit'])->first();
            $product = Product::find($added['product_id']);
            $prevStock=$unit->pivot->stock>0?$unit->pivot->stock:0.00000000001;
            $prevCost=$unit->pivot->cost>0?$unit->pivot->cost:0.00000000001;
            $cost=(($prevStock*$prevCost)+($added['cant']*$added['cost']))/abs(($prevStock+$added['cant']));
            $unit->pivot->stock = removeComma($unit->stock) + removeComma($added['cant']);
            
            ($added['cost']+$unit->pivot->cost)/2;
            $unit->pivot->cost=$cost;
            $unit->pivot->save();
           
            $this->createProvision($product, $added['cant'], $code, $added['unit'],  $added['cost']);
        }
        $this->print($code);
        
        if ($this->setCost) {
           $this->open=true;
           $this->emit('modelOpened');
           $this->render();
           $this->reset('form', 'productAdded', 'count_code');
        } else {
            $this->emit('showAlert', 'Productos añadidos al stock', 'success');
            $this->reset('form', 'productAdded', 'count_code');
        }
        
        

    }
    public function getUnits()
    {
        $prod = Product::find($this->form['product_id']);
        if ($prod) {
            $this->units = $prod->units()->distinct('name')->pluck('name', 'units.id');
            $this->form['unit'] = array_key_first($this->units->toArray());
            $this->updatedFormUnit(array_key_first($this->units->toArray()));
            $this->render();
        }
    }
    public function updatedFormUnit($value)
    {
        $prod = Product::find($this->form['product_id']);
        if ($prod) {
            $unit = $prod->units()->where('units.id',$value)->first();
            if ($unit) {
                $this->form['cost']=$unit->pivot->cost;
            }
            $this->render();
        }
    }
}
