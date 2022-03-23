<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Livewire\Component;

class DeleteUser extends Component
{
    public $user_id;
    public function render()
    {
        return view('livewire.users.delete-user');
    }

    public function deleteUser($user_id)
    {
        $user=User::where('id', $user_id)->first();
        $user->delete();
        $this->emit('reloadUsers');
    }
}
