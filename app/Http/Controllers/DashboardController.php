<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $users = auth()->user()->store->users()->with('image')->paginate(8);
        $user=auth()->user();
        if ($user->hasPermissionTo('Ver Utilidad')) {
            return view('dashboard.index', compact('users'));
        } else if ($user->hasPermissionTo('Crear Facturas')) {
            return view('pages.invoices.create');
        } else if($user->hasPermissionTo('Cobrar Facturas')){
            return view('pages.invoices.orders');
        }else {
            return view('pages.settings.index');
        }
        
    }
}
