<?php

namespace App\Http\Livewire\Settings\Roles;

use Database\Seeders\RoleSeeder;
use App\Http\Traits\Livewire\Confirm;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateRole extends Component
{
    public $form;
    use Confirm;
    protected $listeners=['createRole','validateAuthorization'];
    protected $rules=[
        'form.name'=>'required|string|unique:roles,name'
    ];

    public function render()
    {
        return view('livewire.settings.roles.create-role');
    }

    public function createRole()
    {
        $this->validate();
        $this->form['name']=$this->form['name'].auth()->user()->store->id;
        $role=Role::create($this->form);
        auth()->user()->store->roles()->save($role);
        $this->emit('showAlert','El rol ha sido creado exitosamente', 'success');
        $this->reset();
        $this->emit('reloadRoles');
    }
}
