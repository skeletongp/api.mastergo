<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class AssignPermission extends Component
{
    use AuthorizesRequests;
    public  $user;
    public $permissions, $permissionsSelected = [], $selectAll = false, $rolePermissions = [];

    public function mount()
    {
        $this->permissionsSelected = $this->user['permissions'];
        $spatiePermissionCache = Cache::get('spatie.permission.cache')['permissions'];
        $this->permissions = Arr::pluck($spatiePermissionCache, 'n', 'i');
        $this->permissions = array_diff($this->permissions, $this->user['permissionsViaRole']);
        $this->selectAll = count($this->permissionsSelected) === count($this->permissions);
    }

    public function render()
    {
        return view('livewire.users.assign-permission');
    }
    public function changePermissions()
    {
        $this->authorize('Asignar Permisos');
        $user = User::whereId($this->user['id'])->first();
        $user->syncPermissions($this->permissionsSelected);
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
