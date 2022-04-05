<?php

namespace App\Http\Livewire\Settings\Comprobantes;

use App\Models\Invoice;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class CreateComprobante extends Component
{

    use AuthorizesRequests;

    public $form;


    protected $rules=[
        'form.type'=>'required',
        'form.inicial'=>'required|numeric|min:1',
        'form.final'=>'required_with:form.inicial|numeric|gte:form.inicial',

    ];

    public function render()
    {
        return view('livewire.settings.comprobantes.create-comprobante');
    }
    public function createComprobante()
    {
        $this->authorize('Crear Comprobantes');
        $this->validate();

        for ($i=$this->form['inicial']; $i <=$this->form['final'] ; $i++) { 
            auth()->user()->store->comprobantes()->create([
                'type'=>$this->form['type'],
                'prefix'=>Invoice::TYPES[$this->form['type']],
                'number'=>str_pad($i, 8,'0', STR_PAD_LEFT),
            ]);
            $this->emit('refreshLivewireDatatable');
        }
        $this->reset();
    }
}
