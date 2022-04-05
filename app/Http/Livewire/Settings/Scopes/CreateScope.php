<?php

namespace App\Http\Livewire\Settings\Scopes;

use App\Models\Scope;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class CreateScope extends Component
{
    public $form;
    use AuthorizesRequests;
    protected $rules=[
        'form.name'=>'required|string|min:5|max:20|unique:scopes,name'
    ];
    public function render()
    {
        return view('livewire.settings.scopes.create-scope');
    }
    public function createScope()
    {
        $this->validate();
        $this->authorize('Crear Scopes');
        Scope::create($this->form);
        $this->reset();
        $this->emitUp('reloadScopes');
        $this->emit('showAlert','Scope creado exitosamente','success');
    }
}
