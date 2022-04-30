<?php

namespace App\Http\Livewire\Settings\Taxes;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class CreateTax extends Component
{

    use AuthorizesRequests;

    public $form;
    function rules(){
        return [
            'form.name'=>'required|string|max:25|unique:taxes,name,NULL,id,deleted_at,NULL,store_id,'.auth()->user()->store->id.'',
            'form.rate'=>'required|numeric|min:1',
        ];
    }

    public function render()
    {
        return view('livewire.settings.taxes.create-tax');
    }
    public function createTax()
    {
        $this->authorize('Crear Impuestos');
        $this->validate();
        $this->form['rate']=floatval($this->form ['rate'])/100;
        $tax=auth()->user()->store->taxes()->create($this->form);
        setContable($tax, '202', $tax->name.' por Pagar');
        setContable($tax, '103', $tax->name.' por Cobrar');
        $this->emit('showAlert', 'Impuesto registrado exitosamente', 'success');
        $this->reset();
        $this->emit('refreshLivewireDatatable');
    }
}
