<?php

namespace App\Http\Livewire\Settings\Permissions;

use Livewire\Component;
use Permission;
use Role;
class CreatePermission extends Component
{
    public $form;



    protected $rules=[
        'form.name'=>'required|string|unique:permissions,name'
    ];
    public function render()
    {
        return view('livewire.settings.permissions.create-permission');
    }

    public function createPermission()
    {
        $this->validate();
        $permisos=explode(',',$this->form['name']);
        foreach ($permisos as $permiso) {
            $permission=Permission::updateOrCreate(['name'=>$permiso],['name'=>$permiso]);
            Role::where('name','Super Admin')->first()->givePermissionTo($permission);
        };
        $this->emit('showAlert','El permiso ha sido creado exitosamente', 'success');
        $this->reset();
        $this->emit('reloadRoles');
    }
}
