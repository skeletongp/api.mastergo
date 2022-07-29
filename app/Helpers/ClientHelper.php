<?php

use App\Models\Client;
use Illuminate\Support\Facades\Cache;

function clientWithCode($store_id){
    $clients=Cache::get('clientsWithCode_'.$store_id);
    if (!$clients) {
       $clients=Client::where('store_id',$store_id)->get()->pluck('name','code');
         Cache::put('clientsWithCode_'.$store_id, $clients);
    }
    return $clients;
}