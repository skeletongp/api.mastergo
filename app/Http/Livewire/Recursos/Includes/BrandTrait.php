<?php

namespace App\Http\Livewire\Recursos\Includes;

use App\Models\Recurso;

trait BrandTrait
{
    public $marca, $cant, $cost;
    public $brands = [];
    public function rules2()
    {
        return [
            'marca' => 'required|string',
            'cant' => 'required|min:0',
            'cost' => 'required|min:0',
        ];
    }
    public function addBrand()
    {
        $this->validate($this->rules2());
        array_push(
            $this->brands,
            [
                'name' => $this->marca,
                'cant' => $this->cant,
                'cost' => $this->cost,
            ]
        );
        $this->reset('marca', 'cant', 'cost');
    }
    public function createBrands(Recurso $recurso)
    {
        if (count($this->brands)) {
            foreach ($this->brands as $brand) {
                $recurso->brands()->create($brand);
            }
        }
    }
    public function removeBrand($id)
    {
        unset($this->brands[$id]);
        $this->brands=array_values($this->brands);
    }
}
