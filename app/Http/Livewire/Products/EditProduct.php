<?php

namespace App\Http\Livewire\Products;

use App\Http\Traits\Livewire\Confirm;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditProduct extends Component
{
    use Confirm, WithFileUploads;
    public Product $product;
    public $units, $unit, $photo, $photo_path;
    public $taxes, $taxSelected = [];

    protected $listeners = ['reloadEditProduct', 'validateAuthorization','updatePrice'];
    public function mount()
    {
        $this->units = $this->product->units->unique('symbol');
        $taxes = $this->product->taxes()->pluck('name', 'taxes.id');
        $this->taxes = auth()->user()->store->taxes()->pluck('name', 'taxes.id');
        foreach ($this->taxes as $id => $tax) {
            if (in_array($tax, $taxes->toArray())) {
                array_push($this->taxSelected, $id);
            };
        }
        foreach ($this->units as  $un) {
            $this->unit[$un->id] = [
                'price_menor' => floatval($un->plainPriceMenor),
                'price_mayor' => floatval($un->plainPriceMayor),
                'price_special' => floatval($un->plainPriceSpecial),
                'cost' => floatval($un->cost),
                'min' => floatval($un->pivot->min),
            ];
        }
    }
    public function render()
    {
        return view('livewire.products.edit-product');
    }

    protected $rules = [
        'product.name' => 'required',
        'product.type' => 'required',
        'photo'=>'max:2048'

    ];
    protected $rules2 = [
        'unit' => 'required',
        'unit.*.price_menor' => 'required|numeric|min:1',
        'unit.*.price_mayor' => 'required|numeric|min:1',
        'unit.*.price_special' => 'required|numeric|min:0',
        'unit.*.cost' => 'required|numeric|min:0',
        'unit.*.min' => 'required|numeric|min:1',
    ];
    public function updateProduct()
    {
        $this->validate();
        
        if ($this->photo_path) {
            $this->product->image()->updateOrCreate(['imageable_id'=>$this->product->id],[
                'path'=>$this->photo_path
            ]);
           
        }
        $this->product->save();
        $place=auth()->user()->place;
        Cache::forget('products'.$place->id);
        $this->emit('showAlert', 'Producto actualizado correctamente', 'success');
    }
   
    
    public function updatePrice()
    {
        $this->validate($this->rules2);
        foreach ($this->unit as $key => $value) {
            $cost=$value['cost']?:0.00000000001;
            $unit = $this->product->units()->where('units.id', $key)->first();
            $unit->pivot->price_menor = $value['price_menor'];
            $unit->pivot->price_mayor = $value['price_mayor'];
            $unit->pivot->price_special = $value['price_special'];
            $unit->pivot->cost = $value['cost'];
            $unit->pivot->min = $value['min'];
            $unit->pivot->margin = ($value['price_menor'] / $cost) - 1;
            $unit->pivot->save();
            
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
    public function updatedPhoto()
    {
        $this->reset('photo_path');
        $this->validate([
            'photo'=>'image|max:2048'
        ]);
        $this->validate([
            'avatar'=>'image|max:2048'
        ]);
        $path = cloudinary()->upload($this->avatar->getRealPath(),
        [
            'folder' => 'carnibores/products',
            'transformation' => [
                      'height' => 250
             ]
        ])->getSecurePath();
        $this->photo_path = $path;
    }
    public function reloadEditProduct()
    {
        $this->redirect(url()->previous());
    }
}
