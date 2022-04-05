<?php

namespace App\Http\Livewire\Settings\Scopes;

use App\Models\Scope;
use Livewire\Component;

class ScopeIndex extends Component
{
    public $store, $scopes;
    public $selectAll, $scopesSelected=[];
    protected $listeners=['reloadScopes'];
    public function mount()
    {
        $this->store=auth()->user()->store;
        $this->scopes=Scope::pluck('name','id');
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
            $keys=array_keys($this->scopesSelected->toArray());
        } else{
            $keys=array_keys($this->scopesSelected);

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
