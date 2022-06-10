<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Traits\CanPinRecords;
use Spatie\Permission\Models\Role;

class TableUser extends LivewireDatatable
{
    use AuthorizesRequests;
    public $exportable = true;
    public $name = "Tabla Usuarios";
    public  $hideable = "select";
    public $roles;


    public function builder()
    {
        $this->roles = auth()->user()->store->roles;
        return auth()->user()->store->users()->with('roles', 'image')->whereHas('roles', function ($role) {
            $role->where('name', '!=', 'Super Admin');
        })->whereNull('deleted_at');
    }

    public function columns()
    {
        $canEdit = auth()->user()->hasPermissionTo('Editar Usuarios');
        $users = $this->builder()->get()->toArray();
        return [
            Column::callback('id', function ($id) use ($users) {
                $result = arrayFind($users, 'id', $id);
                if ($result['image']) {
                    return view('components.avatar', ['avatar' => $result['image']['path']]);
                } else {
                    return view('components.avatar', ['avatar' => env('NO_IMAGE')]);
                }
            })->defaultSort('asc'),
            Column::name('fullname')->label('Nombre Completo')->searchable(),
            Column::name('name')->label('Nombre')->searchable()->hide(),
            Column::name('lastname')->label('Apellido')->searchable()->hide(),
            Column::name('email')->label('Correo Electrónico')->searchable(),
            Column::name('phone')->label('Teléfono')->searchable(),
            Column::callback(['created_at', 'id'], function ($role, $id) use ($users) {
                $result = arrayFind($users, 'id', $id);
                foreach ($result['roles'] as $key => $rol) {
                    $result['roles'][$key]['name'] = preg_replace('/[0-9]+/', '', $result['roles'][$key]['name']);
                }
                return implode(', ', array_column($result['roles'], 'name'));
            })->label('Rol')->searchable()->hide(),
            Column::name('username')->label('Usuario')->searchable()->hide(),
            Column::name('created_at')->label('Registro')->searchable()->hide(),
            Column::callback(['updated_at', 'id'], function ($updated, $id) use ($users) {
                $result = arrayFind($users, 'id', $id);
                return  view('pages.users.actions', ['user' => $result, 'roles' => $this->roles, 'key' => uniqid()]);
            })->label('Acciones'),

        ];
    }


    public function cellClasses($row, $column)
    {
        return
            'whitespace-normal  text-gray-900 px-6 py-2';
    }
}
