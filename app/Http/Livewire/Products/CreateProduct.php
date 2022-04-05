<?php

namespace App\Http\Livewire\Products;

use App\Models\Product;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateProduct extends Component
{
    use AuthorizesRequests, WithFileUploads;

    public $form, $units, $taxes, $places;
    public  $unitSelected = [], $taxSelected = [], $placeSelected = [];
    public $unit_id, $unit_price, $unit_cost, $unit_margin;
    public $photo, $photo_path;

    protected $rules = [
        'form.name' => 'required|string|max:35',
        'form.description' => 'required|string',
        'unitSelected' => 'required|min:1',
        'placeSelected' => 'required|min:1'

    ];

    protected $rules2 = [
        'unit_id' => 'required',
        'unit_price' => 'required',
        'unit_cost' => 'required',
        'unit_margin' => 'required',
    ];
    public function mount()
    {
        array_push($this->placeSelected, auth()->user()->place->id);
    }
    public function render()
    {
        $this->taxes = auth()->user()->store->taxes()->pluck('name', 'id');
        $this->units = auth()->user()->store->units()->pluck('name', 'id');
        $this->places = auth()->user()->store->places()->pluck('name', 'id');
        return view('livewire.products.create-product');
    }

    public function createProduct()
    {
        $this->authorize('Crear Productos');
        $this->validate();
        $store = auth()->user()->store;
        $product = $store->products()->create(
            $this->form
        );
        if ($this->photo_path) {
            $product->image()->create([
                'path' => $this->photo_path
            ]);
        }
        $this->createTaxes($product);
        $this->attachToPlace($product);
        return redirect()->route('products.index');
    }
    public function createTaxes(Product $product)
    {
        foreach ($this->taxSelected as $tax) {
            $product->taxes()->attach(
                $tax
            );
        }
    }
    public function attachToPlace($product)
    {
        foreach ($this->placeSelected as $placeId) {
            $this->createPrices($product, $placeId);
        }
    }
    public function createPrices(Product $product, $placeId)
    {
        foreach ($this->unitSelected as $uniSel) {
            unset($uniSel['id']);
            $uniSel['place_id'] = $placeId;
            $product->units()->attach(
                $uniSel['unit_id'],
                $uniSel
            );
        }
    }
    public function addUnit()
    {
        sleep(1);
        $this->validate($this->rules2);

        $exist = in_array($this->unit_id, array_column($this->unitSelected, 'unit_id'));
        if (!$exist) {
            array_push($this->unitSelected, [
                'id' => count($this->unitSelected),
                'price' => $this->unit_price,
                'cost' => $this->unit_cost,
                'margin' => $this->unit_margin / 100,
                'unit_id' => $this->unit_id,
                'stock' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $this->reset('unit_price', 'unit_cost', 'unit_margin', 'unit_id');
        $this->render();
    }
    public function updatedUnitMargin()
    {
        $this->unit_cost = str_replace('.', ',', $this->unit_cost);
        $this->unit_cost = str_replace('.', ',', $this->unit_cost);
        $this->unit_price = round(floatval($this->unit_cost) * (1 + (floatval($this->unit_margin) / 100)), 2);
    }
    public function updatedUnitPrice()
    {
        if (!$this->unit_margin && floatval($this->unit_cost) > 0 && floatval($this->unit_price) > 0) {
            $this->unit_margin = round(((floatval($this->unit_price) / (floatval($this->unit_cost))) - 1) * 100, 2);
        }
    }
    public function updatedPhoto()
    {
        $ext = pathinfo($this->photo->getFileName(), PATHINFO_EXTENSION);
        $photo = $this->photo->storeAs('products', date('Y_m_d_H_i_s') . '.' . $ext);
        $this->photo_path = asset("storage/{$photo}");
    }
    public function remove($id)
    {
        unset($this->unitSelected[$id]);
        $this->render();
    }
}
