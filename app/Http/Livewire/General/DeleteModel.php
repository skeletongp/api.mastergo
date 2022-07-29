<?php

namespace App\Http\Livewire\General;

use App\Http\Traits\Livewire\Confirm;
use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DeleteModel extends Component
{
    use Confirm;
    public $msg,  $permission, $class;
    public $model_id, $event = "refreshLivewireDatatable";
    protected $listeners=['validateAuthorization', 'deleteModel'];
    public function render()
    {
        return view('livewire.general.delete-model');
    }
    public function deleteModel()
    {
        
        $model=$this->class::find($this->model_id);
        $model->delete();
        $this->emit('showAlert', "Registro ha sido eliminado", 'success');
        $this->emit($this->event);
    }
    public function forgetCache(){
        switch ($this->class) {
            case Client::class:
                Cache::forget('clientWithCode'.env('STORE_ID'));
                break;
            
            default:
                # code...
                break;
        }
    }
}
