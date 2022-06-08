<?php

namespace App\Http\Livewire\Procesos;

use App\Models\Proceso;
use Illuminate\Support\Facades\App;
use Livewire\Component;
use Livewire\WithPagination;
use DNS1D;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProcesoShow extends Component
{
    use WithPagination, AuthorizesRequests;
    public Proceso $proceso;
    public $status;
    public $statusTitle = 'En Proceso';
    public $productos = [];

    protected $queryString=['status','statusTitle'];

    public function render()
    {
        $proceso = $this->proceso->load('productions');
        $productions = $proceso->productions()->paginate(7);
        return view('livewire.procesos.proceso-show', compact('proceso', 'productions'));
    }
    public function updatedStatus()
    {
       
        if ($this->statusTitle == 'En Proceso') {
            $this->statusTitle = 'Completados';
        } else {
            $this->statusTitle = 'En Proceso';
        }
        $this->render();
    }
}
