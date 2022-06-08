<?php

namespace App\Http\Livewire\General;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DeleteModel extends Component
{
    use AuthorizesRequests;
    public $title,  $permission;
    public $model, $event = "";
    public function render()
    {
        return view('livewire.general.delete-model');
    }
    public function deleteModel()
    {
        $this->authorize($this->permission);
        
        $this->model->delete();
        $this->emit('showAlert', "{$this->title} eliminado", 'success');
        $this->emit($this->event);
    }
}
