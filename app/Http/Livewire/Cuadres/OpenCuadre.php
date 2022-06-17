<?php

namespace App\Http\Livewire\Cuadres;

use App\Http\Traits\Livewire\Confirm;
use Livewire\Component;

class OpenCuadre extends Component
{
    use Confirm;
    public $retirado;
    protected $listeners=['openCuadre','validateAuthorization'];

    protected $rules=[
        'retirado'=>'required|numeric'
    ];
    public function mount()
    {
       $place=auth()->user()->place;
    }
    public function render()
    {   
        return view('livewire.cuadres.open-cuadre');
    }
    public function openCuadre()
    {
        $this->validate();

        return redirect(route('cuadres.index', ['retirado'=>$this->retirado]));
    }
}
