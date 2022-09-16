<?php

namespace App\Http\Livewire\Users;

use App\Http\Traits\Livewire\Confirm;
use App\Models\Store;
use App\Models\User;
use Livewire\Component;

class DeleteUser extends Component
{
    use Confirm;
    public $user_id;
    protected $listeners=['deleteUser','validateAuthorization'];
    public function render()
    {
        return view('livewire.users.delete-user');
    }
    public function deleteUser($data)
    {
        $id=$data['data']['value'];
        $user=User::whereId($id)->first();
        $store=Store::find(env('STORE_ID'));
        $store->users()->detach($user->id);
        $this->emit('showAlert','Usuario eliminado existosamente','success');
        $this->emit('refreshLivewireDatatable');
    }
}
