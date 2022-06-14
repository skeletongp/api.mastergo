<?php

namespace App\Http\Livewire\Reports;

use App\Models\CountMain;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateCount extends Component
{
    public $names, $codes, $origins, $model;
    public $instances, $origin, $code, $name, $model_id;

    protected $rules = [
        'code' => 'required',
        'origin' => 'required',
    ];
    public function render()
    {
        $counts = CountMain::select(DB::raw("CONCAT(code,'-',name) as name, code"))
            ->whereIn('code', $this->codes)
            ->pluck('name', 'code');
        if ($this->model::find(1)->fullname) {
            $this->instances = $this->model::pluck('fullname', 'id');
        } else {
            $this->instances = $this->model::pluck('name', 'id');
        }
        return view('livewire.reports.create-count', compact('counts'));
    }
    public function createCount()
    {
        $name = $this->name;
        $this->validate();
        $place=auth()->user()->place;
        if ($this->model_id) {
            $instance = $this->model::find($this->model_id);
        } else{
            $instance = $place;
        }
        if (!$this->name) {
            $name = $instance->fullname ?: $instance->name;
        }
        setContable($instance, $this->code, $this->origin, $name, $place->id, 1);
        $this->emit('showAlert','Cuenta creada existosamente','success');
        return redirect(route('reports.catalogue'));
    }
}
