<?php

namespace App\Http\Livewire\Productions;

use App\Http\Traits\Livewire\Confirm;
use Livewire\Component;

class CreateProduction extends Component
{
    use Confirm;
    public $proceso;
    public $form, $unit_id;
    protected $listeners=['validateAuthorization','storeProduction'];
    protected $rules=[
        'form'=>'required',
        'unit_id'=>'required|min:1',
        'form.start_at'=>'required',
    ];
    public function render()
    {
        $units=auth()->user()->place->units()->pluck('name','units.id');
        if (count($units->toArray())) {
           $this->unit_id=array_key_first($units->toArray());
        }
        return view('livewire.productions.create-production', get_defined_vars());
    }
    public function storeProduction()
    {
        $this->validate();
        if ($this->proceso->productions()->where('status','!=', 'Completado')->count()) {
            $this->emit('showAlert','Ya hay una producción pendiente','warning');
            return ;
        } 
        $this->form['user_id']=auth()->user()->id;
        $this->form['unit_id']=$this->unit_id;
        $this->proceso->productions()->create($this->form);
        $this->reset('form');
        $this->emit('refreshLivewireDatatable');

    }
}
