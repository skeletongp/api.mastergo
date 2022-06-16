<?php

namespace App\Http\Livewire\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithFileUploads;

class SettingUser extends Component
{
    public User $user;
    public $avatar, $photo_path, $photo_prev;
    public $password, $password_confirmation, $oldPassword;
    use WithFileUploads;

    public function mount()
    {
        $this->user = auth()->user();
    }

    function rules()
    {
        return [
            'user' => 'required',
            'user.name' => 'required|string|max:50',
            'user.lastname' => 'required|string|max:75',
            'user.email' => 'required|string|max:100|unique:moso_master.users,email,' . $this->user->id,
            'user.phone' => 'required|string|max:25',
            'avatar' => 'max:2048'
        ];
    }

    public function render()
    {
        return view('livewire.settings.setting-user');
    }
    public function updatedAvatar()
    {
        $ext = pathinfo($this->avatar->getFileName(), PATHINFO_EXTENSION);
        $path = cloudinary()->upload($this->avatar->getRealPath(),
        [
            'folder' => 'carnibores/avatars',
            'transformation' => [
                      'width' => 250,
                      'height' => 250
             ]
        ])->getSecurePath();
        $this->photo_path = $path;
        $this->photo_prev = $this->user->avatar;
    }

    public function updateUser()
    {
        $this->validate();
        if ($this->photo_path) {
            $this->user->image()->update([
                'path' => $this->photo_path
            ]);
        }
        $this->user->save();
        $this->deletePrevPhoto();
        $this->render();
        $this->resetExcept('user');
        $this->emit('showAlert', 'Datos actualizados correctamente', 'success');
    }
    public function deletePrevPhoto()
    {
        if ($this->photo_path) {
            $file_headers = @get_headers($this->photo_prev);
            $exists = $file_headers[0] === 'HTTP/1.1 200 OK';
            if ($exists && ($this->photo_path != $this->photo_prev)) {
                if (file_exists('storage/avatars/' . basename($this->photo_prev))) {
                    unlink('storage/avatars/' . basename($this->photo_prev));
                }
            }
        }
    }

    public function changePassword()
    {
        $this->validate([
            'password' => 'required|min:8|confirmed',
            'oldPassword' => 'required',
        ]);
        if (Hash::check($this->oldPassword, $this->user->password)) {

            $this->user->update([
                'password' => $this->password
            ]);
            $this->emit('showAlert', 'Contraseña actualizada correctamente', 'success');
        } else {
            $this->emit('showAlert', 'La contraseña ingresada no es correcta', 'error');
        }
        Auth::logout();
        Session::flash('msg', 'success| Por favor, inicie sesión nuevamente');
        return redirect()->route('login');
    }
}
