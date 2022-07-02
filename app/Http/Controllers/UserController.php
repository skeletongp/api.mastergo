<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware(['permission:Ver Usuarios'])->only('index');
        $this->middleware(['permission:Asignar Permisos'])->only('setPermissions');
    }
    
    public function index()
    {
        return view('pages.users.index');
    }
    public function setPermissions($user)
    {
        $user=User::whereId($user)->first();
        $permissions=$user->permissions()->pluck('name','id')->toArray();
        $permissionsViaRole=$user->getPermissionsViaRoles()->pluck('name','id')->toArray();
        $user=array_merge($user->toArray(), ['permissions'=>$permissions, 'permissionsViaRole'=>$permissionsViaRole]);
       return view('pages.users.set-permissions', ['user'=>$user]);
    }

    
    
}
