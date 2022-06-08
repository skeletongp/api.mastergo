<?php

namespace App\Http\Livewire\Settings\Scopes;

use App\Http\Traits\Livewire\Confirm;
use App\Models\Scope;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class ScopeIndex extends Component
{
    use Confirm;
    public $store, $scopes;
    public $selectAll, $scopesSelected=[];
    protected $listeners=['reloadScopes', 'deleteScope'];
    public function mount()
    {
        $this->store=auth()->user()->store;
        $this->scopes=Scope::pluck('name');
        foreach($this->scopes as $id=> $scope){
            if($this->store->hasScope($scope)){
               $this->scopesSelected[$id]=$scope;
            }
        }
        $this->selectAll=count($this->scopes->toArray())==count($this->scopesSelected);
    }

    public function render()
    {
        return view('livewire.settings.scopes.scope-index');
    }
    public function changeScopes()
    {
        if (!is_array($this->scopesSelected)) {
            $keys=array_values($this->scopesSelected->toArray());
        } else{
            $keys=array_values($this->scopesSelected);

        }
        if (!auth()->user()->hasPermissionTo('Editar Scopes')) {
            throw new AuthorizationException();
        }
        $this->store->scope()->sync(
            $keys
        );
        $this->emit('showAlert','Scopes actualizados','success');
    }
    public function updatedSelectAll()
    {
        if ($this->selectAll) {
            $this->scopesSelected = $this->scopes;
        } else {
            $this->scopesSelected = [];
        }
    }
    public function deleteScope($name)
    {
        dd($name);
        $scope=Scope::where('name', $name)->first();
        $scope->delete();
        $this->emit('reloadScopes');
        $this->emit('showAlert','El Scope ha sido eliminado','success');

    }
    public function reloadScopes()
    {
        $this->mount();
    }
}
