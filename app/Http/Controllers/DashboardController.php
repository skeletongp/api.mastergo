<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $users = auth()->user()->store->users()->with('image')->paginate(8);
        return view('dashboard.index', compact('users'));
    }
}
