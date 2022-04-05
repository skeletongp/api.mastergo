<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Spatie\Permission\Models\Role;

class TableUser extends LivewireDatatable
{
    use AuthorizesRequests;
    public $exportable = true;
    public $name = "Tabla Usuarios";
    public $seacheable = ['name'];
    public  $hideable = "select";


    public function builder()
    {
        return auth()->user()->store->users()->whereHas('roles', function ($role) {
            $role->where('name', '!=', 'Super Admin');
        })->whereNull('deleted_at');
    }

    public function columns()
    {
        return [
            NumberColumn::name('id')->defaultSort('asc'),
            Column::raw("CONCAT(users.name,' ',users.lastname) AS Nombre Completo")->searchable(),
            Column::name('email')->label('Correo Electrónico')->searchable(),
            Column::name('phone')->label('Teléfono')->searchable(),
            Column::name('roles.name')->label('Rol')->searchable(),
            Column::name('username')->label('Usuario')->searchable()->hide(),
            Column::name('created_at')->label('Registro')->searchable()->hide(),
            auth()->user()->hasPermissionTo('Borrar Usuarios') || auth()->user()->hasPermissionTo('Editar Usuarios') ?
                Column::callback(['id', 'name'], function ($id, $name) {
                    return view('pages.users.actions', ['user' => User::where('id', $id)->first()]);
                })->label('Acciones')->unsortable()->excludeFromExport()->unsortable() :
                Column::name('updated_at')->label('Actualizado')->hide(),

        ];
    }

    public function getRolesProperty()
    {
        return Role::pluck('name');
    }
    public function cellClasses($row, $column)
    {
        return
            'whitespace-normal text-base text-gray-900 px-6 py-2';
    }
}
