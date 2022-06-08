<?php

namespace App\Http\Livewire\Procesos;

use App\Http\Traits\Livewire\Confirm;
use App\Models\Proceso;
use App\Models\Product;
use App\Models\Recurso;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class CreateProceso extends Component
{
    public $form;
    use Confirm;
    
    protected $listeners=['validateAuthorization','storeProceso'];
    
    protected $rules=[
        'form'=>'required',
        'form.name'=>'required|string|max:55|unique:procesos,name'
    ];

    public function render()
    {
        $num=auth()->user()->place->procesos()->count();
        $this->form['code']=str_pad($num+1, 4,'0', STR_PAD_LEFT);
        return view('livewire.procesos.create-proceso');
    }
    public function storeProceso()
    {
        $this->validate();
        $place=auth()->user()->place;
        $place->procesos()->create(
            $this->form
        );
        $this->emit('refreshLivewireDatatable');;
        $this->reset();
    }
}
