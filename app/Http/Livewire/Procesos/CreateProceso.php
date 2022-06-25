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
    public $form, $units;
    use Confirm;
    
    protected $listeners=['validateAuthorization','storeProceso'];
    
    protected $rules=[
        'form'=>'required',
        'form.name'=>'required|string|max:55|unique:procesos,name',
        'form.unit_id'=>'required|numeric|exists:units,id'
    ];

    public function render()
    {
        $place=auth()->user()->place;
        $store=auth()->user()->store;
        $num=$place->procesos()->count();
        $this->form['code']=str_pad($num+1, 4,'0', STR_PAD_LEFT);
        $this->units=$store->units()->pluck('name','id');
        return view('livewire.procesos.create-proceso');
    }
    public function storeProceso()
    {
        $this->validate();
        $place=auth()->user()->place;
        $this->form['user_id']=auth()->id();
        $place->procesos()->create(
            $this->form
        );
        $this->emit('refreshLivewireDatatable');;
        $this->reset();
    }
}
