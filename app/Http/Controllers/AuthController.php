<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login()
    {
        if (auth()->user()) {
            return view('home');
        }
        return view('auth.login');;
    }
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'exists:users,username'
        ]);
        if (Auth::attempt($request->only('username', 'password'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }
        Session::flash('msg','Datos inválidos');
        return redirect()->route('login');
    }
}
