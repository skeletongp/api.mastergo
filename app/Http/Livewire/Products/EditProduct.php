<?php

namespace App\Http\Livewire\Products;

use App\Models\Product;
use App\Models\Unit;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class EditProduct extends Component
{
    use AuthorizesRequests;
    public Product $product;
    public $units, $unit;
    public $taxes, $taxSelected = [];

    protected $listeners = ['reloadEditProduct'];
    public function mount()
    {
        $this->units = $this->product->units->unique('symbol');
        $taxes = $this->product->taxes->pluck('name', 'id');
        $this->taxes = auth()->user()->store->taxes->pluck('name', 'id');
        foreach ($this->taxes as $id => $tax) {
            if (in_array($tax, $taxes->toArray())) {
                array_push($this->taxSelected, $id);
            };
        }
        foreach ($this->units as  $un) {
            $this->unit[$un->symbol] = [
                'price' => floatval($un->plainPrice),
                'cost' => floatval($un->cost),
            ];
        }
    }
    public function render()
    {
        return view('livewire.products.edit-product');
    }

    protected $rules = [
        'product.name' => 'required',
        'product.description' => 'required',

    ];
    protected $rules2 = [
        'unit' => 'required',
        'unit.*.price' => 'required|numeric|min:1',
        'unit.*.cost' => 'required|numeric|min:1',
    ];
    public function updateProduct()
    {
        $this->validate();
        $this->product->save();
        $this->emit('showAlert', 'Producto actualizado correctamente', 'success');
    }
    public function updatePrice()
    {
        $this->authorize('Cambiar Precios');
        $this->validate($this->rules2);
        foreach ($this->unit as $key => $value) {
            $unit = $this->product->units()->where('symbol', $key)->first();
            $unit->pivot->price = $value['price'];
            $unit->pivot->cost = $value['cost'];
            $unit->pivot->margin = ($value['price'] / $value['cost']) - 1;
            $unit->pivot->save();
            $this->unit[$key] = [
                'price' => floatval($unit->pivot->price),
                'cost' => floatval($unit->pivot->cost),
            ];
        }
        $this->emit('showAlert', 'Precios Actualizados', 'success');
        $this->emit('reloadEditProduct');
    }
    public function updateTax()
    {
        $this->product->taxes()->sync($this->taxSelected);
        $this->emit('showAlert', 'Impuestos actualizados', 'success');
        $this->emit('reloadEditProduct');
    }

    public function deleteUnit($unit)
    {
        if ($this->product->units->count() !== 1) {
            $unit = $this->product->units()->where('symbol', $unit)->first();
            $unit->pivot->delete();
            $this->emit('showAlert', 'Precio removido exitosamente', 'success');
            $this->emit('reloadEditProduct');
            return;
        }
        $this->emit('showAlert', 'No se puede eliminar la única medida registrada', 'error');
        return;
    }

    public function reloadEditProduct()
    {
        $this->redirect(url()->previous());
    }
}
