<?php

namespace App\Http\Livewire\Recursos;

use Livewire\Component;

class RecursoAddBrand extends Component
{
    public $name, $cost, $recurso;

    public function mount($recurso)
    {
        $this->recurso = $recurso;
    }

    public function render()
    {
        return view('livewire.recursos.recurso-add-brand');
    }
    public function addBrand()
    {
        $this->validate([
            'name' => 'required',
            'cost' => 'required',
        ]);
        $this->recurso->brands()->create([
            'name' => $this->name,
            'cost' => $this->cost,
        ]);
        $this->reset('name', 'cost');
        $this->emit('refreshLivewireDatatable');
    }
}
