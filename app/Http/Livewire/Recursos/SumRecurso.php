<?php

namespace App\Http\Livewire\Recursos;

use App\Http\Livewire\Products\Includes\SumProductTrait;
use App\Models\Brand;
use App\Models\Condiment;
use App\Models\Provider;
use App\Models\Provision;
use App\Models\Recurso;
use Cloudinary\Transformation\IfElse;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SumRecurso extends Component
{

    use SumProductTrait;
    public $recursos, $brands, $count_code, $ref = 'N/D', $counts, $provider_id;
    public $efectivo = 0, $tarjeta = 0, $transferencia = 0, $banks, $bank_id, $ref_bank, $tax = 0, $discount = 0;
    public $recurso, $cant, $brand_id, $recurso_id, $recurso_type;
    public $selected = [], $setCost = false, $total = 0, $hideButton = true;

    protected $rules = [
        'recurso' => 'max:2555',
        'selected' => 'required|min:1',
        'provider_id' => 'required|min:1',
    ];
    protected $queryString = ['selected', 'setCost', 'total', 'discount'];

    public function mount()
    {
        $place = auth()->user()->place;
        $store = auth()->user()->store;
        $recursos = $place->recursos()->select(DB::raw('name, CONCAT(id,"|" ,"App\\\Models\\\Recurso") as id'))->pluck('name', 'id');
        $condiments = $place->condiments()->select(DB::raw('name, CONCAT(id,"|" ,"App\\\Models\\\Condiment") as id'))->pluck('name', 'id');
        $this->recursos = $recursos->merge($condiments);
        $this->providers = $store->providers()->pluck('fullname', 'providers.id');
        $this->units = $place->units()->pluck('name', 'units.id');
        $this->counts = $place->counts()->where('code', 'like', '104%')->pluck('name', 'code');
        $this->banks = $store->banks()->select(DB::raw('CONCAT(bank_name," ",bank_number) AS name, id'))->pluck('name', 'id');
    }

    public function render()
    {
        return view('livewire.recursos.sum-recurso');
    }
    public function updatedRecurso()
    {
        if ($this->recurso) {
            $data = explode('|', $this->recurso);
            $this->recurso_id = $data[0];
            $this->recurso_type = $data[1];
            $this->updatedRecursoId();
        }
    }
    public function updatedRecursoId()
    {
        $this->reset('brands');
        $this->recurso = null;
        if ($this->recurso_type == 'App\Models\Recurso') {
            $this->recurso = Recurso::whereId($this->recurso_id)->with('brands')->first();
            if ($this->recurso) {
                $this->brands = $this->recurso->brands()->pluck('name', 'id');
                $this->brand_id = null;
            }
        } else {
            $this->recurso = Condiment::whereId($this->recurso_id)->with('unit')->first();
            if ($this->recurso) {
                $this->brands = $this->recurso->unit()->pluck('name', 'id');
                $this->brand_id = null;
            }
        }
    }

    public function addSelected()
    {
        $this->validate([
            'brand_id' => 'required',
            'cant' => 'required',
            'recurso' => 'required',
        ]);
        if ($this->recurso_type == 'App\Models\Recurso') {
            $brand = Brand::whereId($this->brand_id)->with('recurso')->first();
            $this->total += $brand->cost * $this->cant;
            array_push(
                $this->selected,
                [
                    'recurso_id' => $brand->recurso_id,
                    'recurso' => $brand->recurso->name,
                    'brand_id' => $brand->id,
                    'brand' => $brand->name,
                    'cant' => $this->cant,
                    'type' => $this->recurso_type,
                    'cost' => $brand->cost * $this->cant
                ]
            );
        } else {

            $this->total += $this->recurso->cost * $this->cant;
            array_push(
                $this->selected,
                [
                    'recurso_id' => $this->recurso_id,
                    'recurso' => $this->recurso->name,
                    'brand_id' => $this->recurso->unit_id,
                    'brand' => $this->recurso->unit->name,
                    'cant' => $this->cant,
                    'type' => $this->recurso_type,
                    'cost' => $this->recurso->cost * $this->cant
                ]
            );
        }

        $this->reset('brand_id', 'cant', 'recurso_id', 'recurso', 'recurso_type', 'brands');
    }
    public function revalidate()
    {
        if ($this->setCost) {
            $this->rules = array_merge($this->rules, ['count_code' => 'required']);
            $this->rules = array_merge($this->rules, ['efectivo' => 'required']);
            $this->rules = array_merge($this->rules, ['tarjeta' => 'required']);
            $this->rules = array_merge($this->rules, ['transferencia' => 'required']);
            $this->rules = array_merge($this->rules, ['tax' => 'required']);
        }
        if ($this->transferencia > 0) {
            $this->rules = array_merge($this->rules, ['bank_id' => 'required']);
            $this->rules = array_merge($this->rules, ['ref_bank' => 'required']);
        }
        $this->validate();
    }
    public function storeSelected()
    {
        $code = Provision::code();
        $this->revalidate();
        foreach ($this->selected as $selected) {
            $cost=0;
            if ($selected['type'] == 'App\Models\Recurso') {
                $brand = Brand::whereId($selected['brand_id'])->with('recurso')->first();
                $recurso = $brand->recurso;
                $brand->update([
                    'cant' => $brand->cant + $selected['cant']
                ]);
                $cost=$brand->cost;
            } else {
                $recurso=Condiment::whereId($selected['recurso_id'])->with('unit')->first();
                $recurso->update([
                    'cant' => $recurso->cant + $selected['cant']
                ]);
                $cost=$recurso->cost;
            }

            $this->createProvision($recurso, $selected['cant'], $code, $recurso->unit_id,  $cost);
        }
        if ($this->setCost) {
            $provider = Provider::whereId($this->provider_id)->first();
            $outcome = setOutcome($this->total, 'Ingreso de productos a inventario', $this->ref);
            $provider->outcomes()->save($outcome);
            $this->createPayment($outcome, $code);
            $place = auth()->user()->place;
            $provisions = Provision::wherecode($code)->get();
            $this->emit('printProvision', $provisions);
        }

        $this->reset('brand_id', 'cant', 'selected', 'total','provider_id','count_code');
        $this->emit('showAlert', 'Stock actualizado con éxito', 'success');
    }
    public function removeRecurso($id)
    {
        $this->total -= $this->selected[$id]['cost'];
        unset($this->selected[$id]);
        $this->selected = array_values($this->selected);
    }
}
