<?php

namespace App\Http\Livewire\General;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class DeleteModel extends Component
{

    public $title,  $permission;
        public $model, $event="";
    public function render()
    {
        return view('livewire.general.delete-model');
    }
    public function deleteModel()
    {
        auth()->user()->hasPermissionTo($this->permission);
        $this->model->delete();
        $this->emit('showAlert',"{$this->title} eliminado", 'success');
        $this->emit($this->event);
    }
}
