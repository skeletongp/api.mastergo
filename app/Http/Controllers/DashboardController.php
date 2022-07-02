<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $users = auth()->user()->store->users()->with('image')->paginate(8);
        $user=auth()->user();
        $rolePermissions=$user->getPermissionsViaRoles();
        $userPermissions=$user->permissions;
        $permissions=array_merge($rolePermissions->toArray(),$userPermissions->toArray());
        if (in_array('Ver Utilidad', array_column($permissions, 'name'))) {
            return view('dashboard.index', compact('users'));
        } else if (in_array('Crear Facturas', array_column($permissions, 'name'))) {
            return view('pages.invoices.create');
        } else if(in_array('Cobrar Facturas', array_column($permissions, 'name'))){
            return view('pages.invoices.orders');
        }else {
            return view('pages.settings.index');
        }
        
    }
}
