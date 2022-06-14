<?php

namespace App\Http\Livewire\Productions;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductProduction;
use App\Models\Recurso;
use App\Models\Unit;
use Livewire\Component;

class GetProductFromProduction extends Component
{
    public $production;
    public $products, $units;
    public $product, $unit;
    public $product_id, $unit_id, $cant, $selected = [], $productible_type, $productible_id, $unitable_type, $unitable_id, $pivotId;

    public function render()
    {
        $products = auth()->user()->place->products()->orderBy('name')->with('units')->get();
        $recursos = auth()->user()->place->recursos()->orderBy('name')->with('brands')->get();
        $collect = collect($products);
        $collect2 = collect($recursos);
        $this->products = $collect->merge($collect2);

        return view('livewire.productions.get-product-from-production');
    }
    public function addSelected()
    {
        $this->validate([
            'cant' => 'required|min:1',
            'product_id' => 'required',
            'unit_id' => 'required',
        ]);
        array_push($this->selected, [
            'productible_type' => $this->productible_type,
            'productible_id' => $this->productible_id,
            'unitable_type' => $this->unitable_type,
            'unitable_id' => $this->unitable_id,
            'cant' => $this->cant,
            'product' => $this->product->name,
            'unit' => $this->unit->name,
            'pivotId' => $this->pivotId,

        ]);
        $this->reset('productible_type', 'productible_id', 'unitable_type', 'unitable_id', 'cant', 'pivotId','product_id','unit_id');
    }
    public function updatedProductId()
    {
        $data = explode('|', $this->product_id);
        $this->productible_id = $data[0];
        $this->productible_type = $data[1];
        if ($data[1] == Product::class) {
            $this->product = Product::whereId($data[0])->with('units')->first();
            $this->units = $this->product->units;
        } else {
            $this->product = Recurso::whereId($data[0])->with('brands')->first();
            $this->units = $this->product->brands;
        }
    }
    public function updatedUnitId()
    {
        $data = explode('|', $this->unit_id);
        $this->unitable_id = $data[0];
        $this->unitable_type = $data[1];
        if ($data[1] == Unit::class) {
            $this->unit = $this->product->units()->where('units.id', $data[0])->first();
            $this->pivotId = $this->unit->pivot->id;
        } else {
            $this->unit = $this->product->brands()->where('id', $data[0])->first();
            $this->pivotId = $this->unit->id;
        }
    }
    public function storeSelected()
    {
        foreach ($this->selected as $item) {
            ProductProduction::create([
                'production_id' => $this->production['id'],
                'productible_type' => $item['productible_type'],
                'productible_id' => $item['productible_id'],
                'unitable_type' => $item['unitable_type'],
                'unitable_id' => $item['unitable_id'],
                'cant' => $item['cant'],
            ]);
            $this->sumStock($item);
        }
        $this->emit('render');
        $this->reset('productible_type', 'productible_id', 'unitable_type', 'unitable_id', 'cant', 'pivotId', 'selected');
        $this->emit('showAlert', 'Resultados registrados exitosamente', 'success');
    }
    public function sumStock($data)
    {
        if ($data['productible_type'] == Product::class) {
            $user = auth()->user();
            $unit = $user->place->units()->wherePivot('id', $data['pivotId'])->first();
            $unit->pivot->stock = $unit->pivot->stock + $data['cant'];
            $unit->pivot->save();
        } else {
            $brand = Brand::whereId($data['pivotId'])->first();
            $brand->cant = $brand->cant + $data['cant'];
            $brand->save();
        }
    }
    public function removeRecurso($id)
    {
        unset($this->selected[$id]);
        $this->selected=array_values($this->selected);
    }
}