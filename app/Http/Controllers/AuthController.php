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
            return view('welcome');
        }
        return view('auth.login');;
    }
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'exists:users,username'
        ]);
        if (Auth::attempt($request->only('username', 'password'), false)) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }
        Session::flash('msg','error| Los datos ingresados son incorrectos');
        return redirect()->route('login');
    }
    public function logout()
    {
        if (Auth::check()) {
           Auth::logout();
        }
        Session::flash('msg','success| La sesión ha sido cerrada');
        return redirect()->route('login');
    }
}
