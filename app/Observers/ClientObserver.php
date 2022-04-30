<?php

namespace App\Observers;

use App\Models\Client;
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
        $client->fullname = (string) rtrim($client->lastname).', '.$client->name;
    }
   
    public function updated(Client $client)
    {
        //
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
