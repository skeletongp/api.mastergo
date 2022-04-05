<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class AssignPermission extends Component
{
    use AuthorizesRequests;
    public User $user;
    public $permissions, $permissionsSelected = [], $selectAll = false, $rolePermissions=[];

    public function mount()
    {
        $this->rolePermissions = $this->user->roles()->first()->permissions->pluck('name');

        $userPermissions=$this->user->permissions->pluck('name');
        $this->permissionsSelected=$this->rolePermissions->union($userPermissions);
        $this->permissions = Permission::pluck('name', 'id');
        $this->selectAll = count($this->permissionsSelected) === count($this->permissions);
    }

    public function render()
    {
        $this->permissions = Permission::pluck('name', 'id');
        return view('livewire.users.assign-permission');
    }
    public function changePermissions()
    {
            $this->authorize('Asignar Permisos');
            $this->user->syncPermissions($this->permissionsSelected);
            $this->emit('showAlert', 'Permisos actualizados', 'success');
            $this->render();
       
    }
    public function updatedSelectAll()
    {
        if ($this->selectAll) {
            $this->permissionsSelected = $this->permissions;
        } else {
            $this->permissionsSelected = [];
        }
    }
}
