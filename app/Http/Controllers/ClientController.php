<?php

namespace App\Http\Controllers;

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
    public function invoices($client_id)
    {
        return view('pages.clients.client-invoice', ['client_id'=>$client_id]);
    }
}
