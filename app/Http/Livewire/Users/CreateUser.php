<?php

namespace App\Http\Livewire\Users;

use App\Models\Store;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateUser extends Component
{
    public $form, $avatar, $photo_path, $store_id, $role;
    use WithFileUploads;
    public function render()
    {
        $store=auth()->user()->store;
        $this->store_id=$store->id;
        $roles=$store->roles()->pluck('name');
        $places=$store->places()->pluck('name','id');
        $this->form['place_id']=array_key_first($places->toArray());
        return view('livewire.users.create-user',
        [
            'roles'=>$roles,
            'places'=>$places
        ]);
    }

    protected $rules = [
        'form.name' => 'required|string|max:50',
        'form.lastname' => 'required|string|max:75',
        'form.email' => 'required|string|max:100|unique:users,email',
        'form.username' => 'required|string|max:35|unique:users,username',
        'form.password' => 'required|string|min:8',
        'form.phone' => 'required|string|max:25',
        'form.place_id' => 'required|numeric|exists:places,id',
        'avatar' => 'max:2048',
        'role'=>'required|exists:roles,name'
    ];

    public function createUser()
    {
        $this->validate();
        $store=auth()->user()->store;
        $user= $store->users()->create($this->form);
        if ($this->photo_path) {
            $user->image()->create([
                'path'=>$this->photo_path
            ]);
        }
        $user->assignRole($this->role);
        setContable($user, '102');
        $this->reset();
        $this->emit('showAlert','Usuario registrado exitosamente','success');
        $this->emit('refreshLivewireDatatable');
    }
    public function updatedAvatar()
    {
        $ext = pathinfo($this->avatar->getFileName(), PATHINFO_EXTENSION);
        $photo = $this->avatar->storeAs('avatars', date('Y_m_d_H_i_s') . '.' . $ext);
        $this->photo_path = asset("storage/{$photo}");
    }
   
    
}
