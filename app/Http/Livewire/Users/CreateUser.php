<?php

namespace App\Http\Livewire\Users;

use App\Models\Store;
use Illuminate\Support\Facades\Config;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateUser extends Component
{
    public $form, $avatar, $photo_path;
    use WithFileUploads;
    public function render()
    {

        return view('livewire.users.create-user');
    }

    protected $rules = [
        'form.name' => 'required|string|max:50',
        'form.lastname' => 'required|string|max:75',
        'form.email' => 'required|string|max:100|unique:users,email',
        'form.username' => 'required|string|max:35|unique:users,username',
        'form.password' => 'required|string|min:8',
        'form.phone' => 'required|string|max:25',
        'avatar' => 'max:2048'
    ];

    public function createUser()
    {
        $this->validate();
        $store=Store::first();
        $user= $store->users()->create($this->form);
        if ($this->photo_path) {
            $user->image()->create([
                'path'=>$this->photo_path
            ]);
        }
        $this->reset();
        $this->emit('reloadUsers', $user->username);
    }
    public function updatedAvatar()
    {
        $ext = pathinfo($this->avatar->getFileName(), PATHINFO_EXTENSION);
        $photo = $this->avatar->storeAs('avatars', date('Y_m_d_H_i_s') . '.' . $ext);
        $this->photo_path = asset("storage/{$photo}");
    }
}
