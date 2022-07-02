<?php

namespace App\Http\Livewire\Recursos;

use Livewire\Component;

class RecursosHistory extends Component
{
    public $recurso;
    public function render()
    {
        return view('livewire.recursos.recursos-history');
    }
}
