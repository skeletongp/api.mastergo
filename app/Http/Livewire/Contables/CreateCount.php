<?php

namespace App\Http\Livewire\Contables;

use App\Models\CountMain;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateCount extends Component
{
    public $names, $codes, $origins, $model, $counts, $chooseModel = false;
    public $instances, $origin, $code, $name, $model_id;
    public $clases = [];
    protected $rules = [
        'code' => 'required',
        'origin' => 'required',
    ];
    function mount($model)
    {
        $this->clases = [
            'Clientes' => 'App\Models\Client',
            'Bancos' => 'App\Models\Bank',
            'Productos' => 'App\Models\Product',
            'Proveedores' => 'App\Models\Provider',
            'Recursos' => 'App\Models\Recurso',
            'Usuarios' => 'App\Models\User',
        ];
        $this->model = $model;
        $this->counts = CountMain::select(DB::raw("CONCAT(code,'-',name) as name, code"))
            ->whereIn('code', $this->codes)
            ->pluck('name', 'code');
        if ($model) {
            $mod = $this->model::first();
            if ($mod && $mod->fullname) {
                $this->instances = $this->model::pluck('fullname', 'id')->toArray();
            } else if ($mod && $mod->bank_name) {
                $this->instances = $this->model::pluck('bank_name', 'id')->toArray();
            } else if ($mod && $mod->contact) {
                dd($model->contact);
            } else if ($mod) {
                $this->instances = $this->model::pluck('name', 'id')->toArray();
            }
        } else {
            $this->instances = [];
        }
    }
    public function render()
    {

        return view('livewire.contables.create-count');
    }
    public function updatedModel($model)
    {
        if ($model) {
            $mod = $this->model::first();
            if ($mod && $mod->fullname) {
                $this->instances = $this->model::pluck('fullname', 'id')->toArray();
            } else if ($mod && $mod->bank_name) {
                $this->instances = $this->model::pluck('bank_name', 'id')->toArray();
            } else if ($mod && $mod->contact) {
                $this->instances = $this->model::join('contacts', 'contacts.client_id', '=', 'clients.id')
                ->select(DB::raw("CONCAT(contacts.fullname,' - ',clients.name) as fullname, clients.id"))
                ->orderBy('fullname')
                ->pluck('contacts.fullname', 'clients.id')
                ->all();
            } else if ($mod) {
                $this->instances = $this->model::pluck('name', 'id')->toArray();
            }
        } else {
            $this->instances = [];
        }
        $this->render();
    }
    public function createCount()
    {
        $name = $this->name;
        $this->validate();
        $place = auth()->user()->place;
        if ($this->model_id) {
            $instance = $this->model::find($this->model_id);
        } else {
            $instance = $place;
        }
        $name=$this->getNameFromInstance($instance);
        setContable($instance, $this->code, $this->origin, $name, $place->id, 1);
        $this->emit('showAlert', 'Cuenta creada existosamente', 'success');
        return redirect(route('contables.catalogue'));
    }
    function getNameFromInstance($instance){
        $class=get_class($instance);
        switch ($class) {
            case 'App\Models\Client':
                return $instance->contact->fullname.' - '.$instance->name;
            case 'App\Models\Bank':
                return $instance->bank_name;
            case 'App\Models\Product':
                return $instance->name;
            case 'App\Models\Provider':
                return $instance->name;
            case 'App\Models\Recurso':
                return $instance->name;
            case 'App\Models\User':
                return $instance->name;
            default:
                return $instance->name;
        }
    }
}
