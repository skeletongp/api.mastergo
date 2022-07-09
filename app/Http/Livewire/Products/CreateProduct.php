<?php

namespace App\Http\Livewire\Products;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateProduct extends Component
{
    use AuthorizesRequests, WithFileUploads;

    public $form=[], $units, $taxes, $places;
    public  $unitSelected = [], $taxSelected = [], $placeSelected = [];
    public $unit_id, $unit_price_mayor, $unit_price_menor, $unit_min, $unit_cost, $unit_margin;
    public $photo, $photo_path;
    public $activeTab="infoproduct";

    protected $queryString=['activeTab','unitSelected'];

    protected $rules = [
        'form.name' => 'required|string|max:35',
        'form.type' => 'required|string|max:35',
        'form.origin' => 'required|string|max:35',
        'unitSelected' => 'required|min:1',
        'placeSelected' => 'required|min:1'

    ];

    protected $rules2 = [
        'unit_id' => 'required',
        'unit_price_mayor' => 'required',
        'unit_price_menor' => 'required',
        'unit_cost' => 'required',
        'unit_min' => 'required',
        'unit_margin' => 'required',
    ];
    public function mount()
    {
        $store=auth()->user()->store;
        $num=$store->products()->count()+1;
        $code=str_pad($num,3,'0', STR_PAD_LEFT);
        $this->form['code']=$code;
        $this->form['type']='Producto';
        $this->form['origin']='Comprado';
        array_push($this->placeSelected, auth()->user()->place->id);
    }
    public function render()
    {
        $this->taxes = auth()->user()->store->taxes()->pluck('name', 'id');
        $this->units = auth()->user()->store->units()->pluck('name', 'id');
        $this->places = auth()->user()->places->pluck('name', 'id');
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
        $this->reset();
        $this->mount();
        $this->emit('showAlert','Producto registrado exitosamente','success');
        $this->emit('refreshLivewireDatatable');
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
                'price_mayor' => $this->unit_price_mayor,
                'price_menor' => $this->unit_price_menor,
                'min' => $this->unit_min,
                'cost' => $this->unit_cost,
                'margin' => $this->unit_margin / 100,
                'unit_id' => $this->unit_id,
                'stock' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $this->reset('unit_price_mayor','unit_price_menor','unit_margin', 'unit_cost', 'unit_margin', 'unit_id');
        $this->render();
    }
    public function updatedUnitMargin()
    {
        $this->unit_cost = str_replace('.', ',', $this->unit_cost);
        $this->unit_cost = str_replace('.', ',', $this->unit_cost);
        $this->unit_price_menor = round(floatval($this->unit_cost) * (1 + (floatval($this->unit_margin) / 100)), 2);
    }
    public function updatedUnitPriceMenor()
    {
        if ( floatval($this->unit_cost) > 0 && floatval($this->unit_price_menor) > 0) {
            $this->unit_margin = round(((floatval($this->unit_price_menor) / (floatval($this->unit_cost?:0.0001))) - 1) * 100, 2);
        } else if(floatval($this->unit_cost)==0){
            $this->unit_margin =100;
        }
    }
    public function updatedPhoto()
    {
        $this->reset('photo_path');
        $this->validate([
            'photo'=>'image|max:2048'
        ]);
        $path = cloudinary()->upload($this->photo->getRealPath(),
        [
            'folder' => 'carnibores/avatars',
            'transformation' => [
                      'width' => 250,
                      'height' => 250
             ]
        ])->getSecurePath();
        $this->photo_path = $path;
    }
   
    public function remove($id)
    {
        unset($this->unitSelected[$id]);
        $this->render();
    }
}
