<?php

namespace App\Http\Livewire\Productions;

use App\Http\Traits\Livewire\Confirm;
use App\Models\Product;
use App\Models\ProductProduction;
use App\Models\Recurso;
use Livewire\Component;

use function PHPUnit\Framework\isInstanceOf;

class ShowProduction extends Component
{
    public $production;
    protected $listeners = ['deleteProduct', 'deleteRecurso', 'validateAuthorization', 'render'];
    use Confirm;
    public function render()
    {
        return view('livewire.productions.show-production');
    }
    public function deleteProduct($data)
    {
        try {
            $production = ProductProduction::whereId($data['data']['value'])->first();
            $product = $production->productible;
            $this->restStock($product, $production->unitable_id, $production->cant);
            $production->delete();
            $this->emit('showAlert','Resultado eliminado','success');
            $this->production=$production->production->load('recursos','unit','brands.recurso','proceso','products.productible','products.unitable');
            $newCant=$this->production->getted-$production->cant;
            $this->production->update([
                'getted' => $newCant,
                'eficiency' => $newCant / $this->production->setted * 100,
            ]);
            $this->render();
        } catch (\Throwable $th) {
            throw $th;
        };
    }
    public function restStock($product, $unit_id, $cant)
    {
        if ($product instanceof Product) {
            $unit = $product->units()->where('units.id', $unit_id)->first()->pivot;
            $unit->stock = $unit->stock - $cant;
            $unit->save();
        } else if ($product instanceof Recurso) {
            $brand = $product->brands()->whereId($unit_id)->first();
            $brand->cant = $brand->cant - $cant;
            $brand->save();
        }
    }
    public function deleteRecurso($id)
    {
    }
}
