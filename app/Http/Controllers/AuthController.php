<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login()
    {
        if (auth()->user()) {
            return redirect(route('home'));
        }
        $store = Store::whereId(env('STORE_ID'))->with('users')->first();
        $users = $store->users()->where('loggeable', 'yes')->pluck('fullname', 'username');
        return view('auth.login', compact('users', 'store'));;
    }
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'exists:moso_master.users,username'
        ]);
        if (Auth::attempt($request->only('username', 'password'), false)) {
            $request->session()->regenerate();
            session()->put('place_id', auth()->user()->place->id);
            if (!Cache::has('scopes_' . env('STORE_ID'))) {
                Cache::put(
                    'scopes_' . env('STORE_ID'),
                    auth()->user()->store->scope()->pluck('name')
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
    public function prueba()
    {

        /*  $products=DB::table('moso_atrionstore.products')
        ->where('store_id',26)->get();
        //create a file json called products
        $file = fopen(public_path('products.json'), 'w');
        $data=[];
        //foreach products, add to json
        $code=0;
        foreach ($products as $product){
            $code++;
            
           array_push($data,[
               'code'=>str_pad($code, 3, "0", STR_PAD_LEFT),
               'name'=>$product->name,
               'unit_id'=>2,
               'Medidida'=>'Unidad',
               'origin'=>'Comprado',
               'price_menor'=>$product->price,
               'price_mayor'=>$product->price,
               'min'=>50,
               'cost'=>$product->cost,
               'stock'=>$product->stock,
           ]);
        }
        fwrite($file, json_encode($data));
        dd($data); */

        $clients = DB::table('moso_atrionstore.clients')
            ->where('store_id', 26)->where('id','!=',134)->get();
        $store = Store::whereId(env('STORE_ID'))->first();
        foreach ($clients as $client) {
            $client = Client::where('phone', $client->phone)->first()->update([
               'rnc'=> $client->rnc ?: '000-' . rand(1111111, 9999999) . '-0'
            ]);
           /*  $client->contact()->create([
                //get first word from client name
                'name' => explode(' ', $client->name)[0],

                //get last word from client name
                'lastname' => explode(' ', $client->name)[count(explode(' ', $client->name)) - 1],
                'cellphone' => $client->phone,
                'cedula' => $client->rnc,
                'phone' => $client->phone,
            ]);
            setContable($client, '101', 'debit', $client->contact->fullname . '-' . $client->name, null, true); */
        }
        return view('prueba');
    }
}
