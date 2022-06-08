<?php

namespace App\Http\Livewire\Recursos;

use App\Models\Recurso;
use Livewire\Component;

class RecursosDetail extends Component
{
    public $brands;
    public Recurso $recurso;
    public function mount()
    {
        $this->brands=$this->recurso->brands;
    }
    public function render()
    {
        return view('livewire.recursos.recursos-detail');
    }
}
