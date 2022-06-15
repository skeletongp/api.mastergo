<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login()
    {
        if (auth()->user()) {
            return redirect( route('home'));
        }
        $store=Store::whereId(env('STORE_ID'))->with('users')->first();
        $users=$store->users()->where('loggeable','yes')->pluck('fullname','username');
        return view('auth.login', compact('users'));;
    }
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'exists:moso_master.users,username'
        ]);
        if (Auth::attempt($request->only('username', 'password'), false)) {
            $request->session()->regenerate();
            session()->put('place_id',auth()->user()->place->id);
            if (!Cache::has('scopes_' . auth()->user()->store->id)) {
                Cache::put(
                    'scopes_' . auth()->user()->store->id,
                    auth()->user()->store->scope->pluck('name')
                );
            }
            return redirect()->intended(route('home'));
        }
        Session::flash('msg', 'error| Los datos ingresados son incorrectos');
        return redirect()->route('login');
    }
    public function logout()
    {
        if (Auth::check()) {
            Cache::forget('place' . auth()->user()->id);
            Cache::forget('store' . auth()->user()->id);
            Auth::logout();
        }
        session()->flush();
        Session::flash('msg', 'success| La sesión ha sido cerrada');
        return redirect()->route('login');
    }
}
