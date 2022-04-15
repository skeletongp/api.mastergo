<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Traits\CanPinRecords;
use Spatie\Permission\Models\Role;

class TableUser extends LivewireDatatable
{
    use AuthorizesRequests, CanPinRecords;
    public $exportable = true;
    public $name = "Tabla Usuarios";
    public  $hideable = "select";


    public function builder()
    {
        return auth()->user()->store->users()->with('roles')->whereHas('roles', function ($role) {
            $role->where('name', '!=', 'Super Admin');
        })->whereNull('deleted_at');
    }

    public function columns()
    {
        $canEdit = auth()->user()->hasPermissionTo('Editar Usuarios');
        return [
            Column::checkbox(),
            NumberColumn::name('id')->defaultSort('asc'),
            Column::name('fullname')->label('Nombre Completo')->searchable(),
            Column::name('name')->label('Nombre')->searchable()->editable($canEdit)->hide(),
            Column::name('lastname')->label('Apellido')->searchable()->editable($canEdit)->hide(),
            Column::name('email')->label('Correo Electrónico')->searchable()->editable($canEdit),
            Column::name('phone')->label('Teléfono')->searchable()->editable($canEdit),
            Column::name('roles.name')->label('Rol')->searchable(),
            Column::name('username')->label('Usuario')->searchable()->hide(),
            Column::name('created_at')->label('Registro')->searchable()->hide(),
            auth()->user()->hasPermissionTo('Borrar Usuarios')  ?
                Column::delete('id')->label('Eliminar') :
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
