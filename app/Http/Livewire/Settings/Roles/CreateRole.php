<?php

namespace App\Http\Livewire\Settings\Roles;

use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateRole extends Component
{
    public $form;
    use AuthorizesRequests;
    public function render()
    {
        return view('livewire.settings.roles.create-role');
    }

    protected $rules=[
        'form.name'=>'required|string|unique:roles,name'
    ];

    public function createRole()
    {
        $this->authorize('Crear Roles');
        $this->validate();
        $this->form['name']=$this->form['name'].rand(0,99);
        $role=Role::create($this->form);
        auth()->user()->store->roles()->save($role);
        $this->emit('showAlert','El rol ha sido creado exitosamente', 'success');
        $this->reset();
        $this->emit('reloadRoles');

    }
}
