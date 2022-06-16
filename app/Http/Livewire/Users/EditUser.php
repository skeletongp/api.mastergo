<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditUser extends Component
{
    public  $user, $isAdmin, $userRoles, $roles;
    public  $avatar, $photo_path, $role, $loggeable=false;
    use WithFileUploads;
    function rules()
    {
        return [
            'user'=>'required',
            'user.name' => 'required|string|max:50',
            'user.lastname' => 'required|string|max:75',
            'user.email' => 'required|string|max:100|unique:moso_master.users,email,'.$this->user['id'],
            'user.phone' => 'required|string|max:25',
            'role'=>'required|exists:roles,name',
          
        ];
    }
    public function mount( $user)
    {
        $this->userRoles=$user['roles'];
        $this->role=count($this->userRoles)?$this->userRoles[0]['name']:'';
        $this->user = $user;
        if($user['loggeable']=='yes'){
            $this->loggeable=true;
        }
    }
    public function render()
    {
        $user=auth()->user();
        $this->roles=$this->roles->pluck('name');
        if (!$user->hasRole('Super Admin')) {
           unset($this->roles[0]);
        }
        return view('livewire.users.edit-user');
    }
    public function updateUser()
    {
        $this->validate();
        $user=User::whereId($this->user['id'])->first();
        if ($this->photo_path) {
            $user=User::whereId($this->user['id'])->first();
            $user->image()->update([
                'path'=>$this->photo_path
            ]);
        }
        $this->user['loggeable']=$this->loggeable?'yes':'no';
        $user->update(Arr::except($this->user,['avatar','pivot','roles','image','updated_at']));
        $user->syncRoles($this->role);
        $user->save();
        Cache::forget(auth()->user()->store->id.'admins');
        $this->emit('refreshLivewireDatatable');
        $this->emit('showAlert', 'Usuario Actualizado Exitosamente','success');
    }
    
    public function updatedAvatar()
    {
        $this->reset('photo_path');
        $this->validate([
            'avatar'=>'image|max:2048'
        ]);
        $path = cloudinary()->upload($this->avatar->getRealPath(),
        [
            'folder' => 'carnibores/avatars',
            'transformation' => [
                      'width' => 250,
                      'height' => 250
             ]
        ])->getSecurePath();
        $this->photo_path = $path;
    }
}
