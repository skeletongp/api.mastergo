<?php

namespace App\Http\Livewire\Procesos;

use App\Models\Recurso;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FormulaProceso extends Component
{
    public $proceso, $form, $recursos, $units, $brands, $type;

    protected $rules = [
        'form.recurso' => 'required',
        'form.cant' => 'required|numeric',
        'form.unit_id' => 'required|numeric',
    ];
    function mount($proceso)
    {
        $this->proceso = $proceso;

        $place = auth()->user()->place;
        $store = auth()->user()->store;
        $recursos = $place->recursos()->select(DB::raw('name, CONCAT(id,"|" ,"App\\\Models\\\Recurso") as id'))->pluck('name', 'id');
        $condiments = $place->condiments()->select(DB::raw('name, CONCAT(id,"|" ,"App\\\Models\\\Condiment") as id'))->pluck('name', 'id');
        $this->units = $store->units()->pluck('name', 'id');
        $this->recursos = $recursos->merge($condiments);
    }
    public function render()
    {

        return view('livewire.procesos.formula-proceso');
    }
    public function updatedFormRecurso($value)
    {
        if ($value) {
            $recurso = explode('|', $value);
            $this->type = $recurso[1];

            if ($recurso[1] == 'App\Models\Recurso') {
                $selRecurso = Recurso::find($recurso[0]);
                $this->brands = $selRecurso->brands()->pluck('name', 'id');
            } else{
                $this->brands = null;
            }
        }
    }
    public function createFormula()
    {
        if ($this->type == 'App\Models\Recurso') {
            $this->rules = array_merge($this->rules, ['form.brand_id' => 'required|numeric']);
        }
        $this->validate($this->rules);
        $formulable = explode('|', $this->form['recurso']);
        $data = [
            'place_id' => auth()->user()->place->id,
            'user_id' => auth()->user()->id,
            'formulable_id' => $formulable[0],
            'formulable_type' => $formulable[1],
            'cant' => $this->form['cant'],
            'unit_id' => $this->form['unit_id'],
            
        ];
        if (array_key_exists('brand_id', $this->form)) {
            $data= array_merge($data, ['brand_id' => $this->form['brand_id']]);
        }
        $this->proceso->formulas()->create($data);
        $this->emit('refreshLivewireDatatable');
        $this->reset('form');
    }
}
