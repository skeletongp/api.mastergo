<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditUser extends Component
{
    public User $user;
    public  $avatar, $photo_path;
    use WithFileUploads;
    function rules()
    {
        return [
            'user.name' => 'required|string|max:50',
            'user.lastname' => 'required|string|max:75',
            'user.email' => 'required|string|max:100|unique:users,email,'.$this->user->id,
            'user.phone' => 'required|string|max:25',
            'avatar'=>'max:2048'
        ];
    }
    public function mount(User $user)
    {
        $this->user = $user;
    }
    public function render()
    {
        return view('livewire.users.edit-user');
    }
    public function updateUser()
    {
        $this->validate();
        if ($this->photo_path) {
           $this->user->image()->update([
            'path'=>$this->photo_path
           ]);
        }
        $this->user->save();
        $this->emitUp('reloadUsers');
    }
    public function updatedUser()
    {
        $this->validate();
        $this->user->save();
        $this->emitUp('reloadUsers');
    }
    public function updatedAvatar()
    {
        $ext = pathinfo($this->avatar->getFileName(), PATHINFO_EXTENSION);
        $photo = $this->avatar->storeAs('/avatars', date('Y_m_d_H_i_s') . '.' . $ext);
        $this->photo_path = asset("storage/{$photo}");
    }
}
