<?php

namespace App\Http\Livewire\Settings\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleIndex extends Component
{
    public $roles, $permissions, $roleActual, $permissionsSelected = [];
    public $role_name , $selectAll = false;

    protected $listeners = ['reloadRoles'];
    protected $queryString = ['role_name'];
    protected $rules = [
        'permissionsSelected.*' => 'required'
    ];
    public function mount()
    {
        $this->role_name=optional(auth()->user()->store->roles()->first())->name;
        $this->roleActual = Role::where('name', $this->role_name)->first();
        if ($this->roleActual) {
            $this->permissionsSelected = $this->roleActual->permissions->pluck('name');
        } else {
            $this->roleActual = Role::where('name', 'Super Admin')->first();
        }
        $this->permissions = Permission::pluck('name', 'id');
        $this->selectAll = count($this->permissionsSelected) === count($this->permissions);
    }

    public function render()
    {
        $this->permissions = Permission::orderBy('name')->pluck('name', 'id');

        $this->roles = auth()->user()->store->roles()->with('permissions')->get();
        return view('livewire.settings.roles.role-index');
    }
    public function reloadRoles()
    {
        $this->render();
    }
    public function updatedRoleName()
    {
        $this->roleActual = Role::where('name', $this->role_name)->first();
        if ($this->roleActual) {
            $this->permissionsSelected = $this->roleActual->permissions()->orderBy('id')->pluck('name');
            $this->selectAll = count($this->permissionsSelected) === count($this->permissions);
        }
        else {
            $this->roleActual = Role::where('name', 'Super Admin')->first();
        }
    }
    public function changePermissions()
    {
        if ($this->roleActual->name == 'Super Admin' || $this->roleActual->name == 'Administrador') {
            $this->emit('showAlert', 'No puede cambiar los permisos', 'warning');
            $this->mount();
        } else {
            $this->roleActual->syncPermissions($this->permissionsSelected);
            $this->emit('showAlert', 'Permisos actualizados', 'success');
            $this->render();
        }
    }
    public function updatedSelectAll()
    {
        if ($this->selectAll) {
            $this->permissionsSelected = $this->permissions;
        } else {
            $this->permissionsSelected = [];
        }
    }
    public function deletePermission($name)
    {
        $noDeletable = ['Crear Roles', 'Crear Permisos', 'Editar Asignar Roles', 'Asignar Permisos', 'Borrar Roles', 'Borrar Permisos'];
        $noDeletable = collect($noDeletable);
        if (!$noDeletable->contains($name)) {
            $permission = Permission::where('name', $name)->first();
            $permission->delete();
            $this->emit('showAlert', 'Permiso eliminado exitosamente', 'success');
        } else {
            $this->emit('showAlert', 'Este permiso no puede ser borrado', 'warning');
        }

        $this->render();
    }
}
