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
function clientWithId(){
  $store_id=env('STORE_ID');
  $clients=Cache::get('clientsWithId_'.$store_id);
  if (!$clients) {
     $clients=Client::where('store_id',$store_id)->get()->pluck('name','id');
       Cache::put('clientsWithId_'.$store_id, $clients);
  }
  return $clients;
    
}
function setDebt($client_id, $payed){
  $client=Client::find($client_id);
  $client->debt=$client->invoices->sum('rest');
  $client->limit=$client->limit+$payed;
  $client->save();
}