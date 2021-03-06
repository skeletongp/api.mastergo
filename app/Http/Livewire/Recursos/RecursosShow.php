<?php

namespace App\Http\Livewire\Recursos;

use App\Models\Recurso;
use Livewire\Component;

class RecursosShow extends Component
{
    public $componentName="recursos.recursos-detail";
    public Recurso $recurso;

    protected $queryString = [
        'componentName'
    ];
    public function render()
    {
        return view('livewire.recursos.recursos-show');

    }

    public function setComponent($name)
    {
        $this->componentName=$name;
        $this->render();
    }
}
