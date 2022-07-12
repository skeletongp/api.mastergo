<?php

namespace App\Http\Livewire\Recurrents;

use App\Models\Recurrent;
use Livewire\Component;

class CreateRecurrent extends Component
{
    public $counts, $form=[];
    protected $rules=[
        'form.name'=>'required',
        'form.amount'=>'required',
        'form.expires_at'=>'required',
        'form.amount'=>'required',
        'form.count_id'=>'required',
    ];
    public function mount()
    {
        $place=auth()->user()->place;
        $this->form['place_id']=$place->id;
        $this->form['recurrency']='Mensual';
        $this->counts = $place->counts()->where('code','like','6%')
        ->selectRaw('CONCAT(code," - ",name) as name, id')
        ->pluck('name','id');
    }
    public function render()
    {
        return view('livewire.recurrents.create-recurrent');
    }
    public function createRecurrent(){
        $this->validate();
        $recurrent=Recurrent::create($this->form);
        $this->reset('form');
        $place=auth()->user()->place;
        $this->form['place_id']=$place->id;
        $this->form['recurrency']='Mensual';
        $this->emit('refreshLivewireDatatable');
    }
}
