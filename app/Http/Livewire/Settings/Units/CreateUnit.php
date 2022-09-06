<?php

namespace App\Http\Livewire\Settings\Units;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class CreateUnit extends Component
{

    use AuthorizesRequests;

    public $form;
    function rules()
    {
        return [
            'form.name' => 'required|string|max:25|unique:units,name,NULL,id,deleted_at,NULL,store_id,' . auth()->user()->store->id . '',
            'form.symbol' => 'required|string|max:3|unique:units,symbol,NULL,id,deleted_at,NULL,store_id,' . auth()->user()->store->id . '',
        ];
    }
    public function render()
    {
        return view('livewire.settings.units.create-unit');
    }

    public function createUnit()
    {
        $this->authorize('Crear Unidades');
        $this->validate();

        auth()->user()->store->units()->create($this->form);
        $this->emit('showAlert', 'Unidad registrada exitosamente', 'success');
        $this->reset();
        Cache::forget('units' . env('STORE_ID'));
        $this->emit('refreshLivewireDatatable');
    }
}
