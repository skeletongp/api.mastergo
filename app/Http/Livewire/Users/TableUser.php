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
    public $padding="px-2";
    public $roles;


    public function builder()
    {
      $users=User::
        join(env('DB_DATABASE').'.store_users','users.id','=','store_users.user_id')
        ->leftJoin(env('DB_DATABASE').'.model_has_roles', 'users.id', '=', env('DB_DATABASE').'.model_has_roles.model_id')
        ->leftjoin(env('DB_DATABASE').'.roles', env('DB_DATABASE').'.model_has_roles.role_id', '=', 'roles.id')
        ->where('users.id','!=',1)
        ->orderBy('users.name')
        ->groupBy('users.id')
        ;
        return $users;
    }

    public function columns()
    {
        
        return [
            Column::name('fullname')->label('Nombre Completo')->searchable(),
            Column::name('name')->label('Nombre')->searchable()->hide(),
            Column::name('lastname')->label('Apellido')->searchable()->hide(),
            Column::name('email')->label('Correo Electrónico')->searchable(),
            Column::name('phone')->label('Teléfono')->searchable(),
            Column::name('roles.name')->label('Rol')->searchable()->hide(),
            Column::name('username')->label('Usuario')->searchable()->hide(),
            Column::name('created_at')->label('Registro')->searchable()->hide(),
            Column::callback(['id'], function ($id)  {
                return  view('pages.users.actions', ['user' => $id,  'key' => uniqid()]);
            })->label('Acciones'),
            

        ];
    }


}
