<?php

namespace App\Observers;

use App\Models\Client;
use App\Models\Store;
use Illuminate\Support\Facades\Cache;

class ClientObserver
{
    
    public function created(Client $client)
    {
    }
    public function creating(Client $client)
    {
        Cache::forget('clients' . $client->store_id);
        Cache::forget('store_' . $client->store_id);
        Cache::forget('place_' . $client->store_id);
        $store=optional(auth()->user())->store?:Store::first();
        $num=$store->clients()->count()+1;
        $code=str_pad($num,4,'0', STR_PAD_LEFT);
        $client->fullname =$client->name.' '.(string) rtrim($client->lastname);
        $client->code = $code;
    }
   
   
    public function updating(Client $client)
    {
        Cache::forget('clients' . $client->store_id);
        $client->fullname = $client->name.' '.(string) rtrim($client->lastname);
    }

    
    public function deleted(Client $client)
    {
        //
    }

  
    public function restored(Client $client)
    {
        //
    }

    
    public function forceDeleted(Client $client)
    {
        //
    }
}
