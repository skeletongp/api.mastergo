<?php

namespace App\Events;

use App\Models\Invoice;
use BeyondCode\LaravelWebSockets\WebSockets\Channels\Channel as ChannelsChannel;
use BeyondCode\LaravelWebSockets\WebSockets\Channels\PrivateChannel as ChannelsPrivateChannel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NewInvoice implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Invoice $invoice;
    

    public function __construct($invoice)
    {
       $this->invoice=$invoice;
    }

    
    public function broadcastOn()
    {
       return new Channel('invoices.'.$this->invoice->place_id);
      
    }
   
}
