<?php

namespace App\Http\Livewire\Settings\Comprobantes;

use App\Jobs\CreateComprobanteJob;
use App\Models\Invoice;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
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
        if($this->form['final']-$this->form['inicial']>999){
            $this->emit('showAlert','No puede añadir más de 1000 comprobantes a la vez','error', 5000);
            return;
        }
        $type=Invoice::TYPES[$this->form['type']];
        Cache::forget($type.'_comprobantes_'.env('STORE_ID'));
        dispatch(new CreateComprobanteJob($this->form))->onconnection('sync');
        $this->emit('showAlert', 'Comprobantes se están añadiendo en segundo plano','success');
        $this->reset();
    }
}
