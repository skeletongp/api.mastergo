<?php

namespace App\Http\Livewire\Settings\Comprobantes;

use App\Jobs\CreateComprobanteJob;
use App\Models\Invoice;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Artisan;
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
        dispatch(new CreateComprobanteJob($this->form))->onconnection('sync');
        $this->emit('showAlert', 'Comprobantes se están añadiendo en segundo plano','success');
        $this->reset();
    }
}
