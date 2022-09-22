<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    public function __construct() {
        $this->middleware(['permission:Ver Clientes']);
    }

    public function index()
    {
        return view('pages.clients.index');
    }
    public function show($client_id)
    {
        $client=Client::find($client_id);
        $client->debt=$client->invoices->sum('rest');
        $client->save();
        return view('pages.clients.show', compact('client'));
    }

    public function invoices($client_id)
    {
        return view('pages.clients.client-invoice', ['client_id'=>$client_id]);
    }

    public function paymany(Request $request, $invoices){
        $invoices=$invoices;
        return view('pages.clients.paymany', compact('invoices'));
    }
    public function printMany(Request $request, $invoices){
        $invoices=$invoices;
        return view('pages.clients.printmany', compact('invoices'));
    }
}
