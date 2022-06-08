<?php

namespace App\Http\Livewire\Users;

use App\Http\Traits\Livewire\Confirm;
use App\Models\User;
use Livewire\Component;

class DeleteUser extends Component
{
    use Confirm;
    public $user;
    protected $listeners=['deleteUser','validateAuthorization'];
    public function render()
    {
        return view('livewire.users.delete-user');
    }
    public function deleteUser($data)
    {
        $id=$data['data']['value'];
        $user=User::whereId($id)->first();
        $user->delete();
        $this->emit('showAlert','Usuario eliminado existosamente','success');
        $this->emit('refreshLivewireDatatable');
    }
}
