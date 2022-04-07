<?php

namespace App\Http\Livewire\Recursos;

use App\Models\Recurso;
use Livewire\Component;

class RecursosDetail extends Component
{
    public $procesos;
    public Recurso $recurso;
    public function mount()
    {
        $this->procesos=$this->recurso->procesos->pluck('name','id');
    }
    public function render()
    {
        return view('livewire.recursos.recursos-detail');
    }
}
