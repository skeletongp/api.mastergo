<?php

namespace App\Http\Livewire\Users;

use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateUser extends Component
{
    public $form, $avatar, $photo_path, $store_id, $role, $loggeable;
    use WithFileUploads;
    public function render()
    {
        $store=auth()->user()->store;
        $this->store_id=$store->id;
        $roles=$store->roles()->pluck('name');
        $places=auth()->user()->places->pluck('name','id');
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
        'form.email' => 'required|string|max:100|unique:moso_master.users,email,NULL,id,deleted_at,NULL',
        'form.username' => 'required|string|max:35|unique:moso_master.users,username,NULL,id,deleted_at,NULL',
        'form.password' => 'required|string|min:8',
        'form.phone' => 'required|string|max:25',
        'form.place_id' => 'required|numeric|exists:places,id',
        'role'=>'required|exists:roles,name'
    ];

    public function createUser()
    {
        $this->validate();
        $store=auth()->user()->store;
        $this->form['loggeable']=$this->loggeable?'yes':'no';
        $this->form['store_id']=auth()->user()->store_id;
        $user= $store->users()->create($this->form);
        if ($this->photo_path) {
            $user->image()->create([
                'path'=>$this->photo_path
            ]);
        }
        $user->assignRole($this->role);
        setContable($user, '102', 'credit');
        Cache::forget($store->id.'admins');
        $this->reset();
        $this->emit('showAlert','Usuario registrado exitosamente','success');
        $this->emit('refreshLivewireDatatable');
    }
    public function updatedAvatar()
    {
        $this->reset('photo_path');
        $this->validate([
            'avatar'=>'image|max:2048'
        ]);
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
    } 
   public function updatedFormLastname($value)
   {
      $name='';
      if (array_key_exists('name',$this->form)) {
        $name=$this->form['name'];
      }
      $username=strtolower(substr($name,0,1).strtok($value, " "));
      $this->form['username']=preg_replace('/[^A-Za-z0-9\-]/', '', $username);
   }
   public function updatedFormName($value)
   {
      $lastname='';
      if (array_key_exists('lastname',$this->form)) {
        $lastname=$this->form['lastname'];
      }
      $username=strtolower(substr($value,0,1).strtok($lastname, " "));
      $this->form['username']=preg_replace('/[^A-Za-z0-9\-]/', '', $username);
   }
   
    
}
