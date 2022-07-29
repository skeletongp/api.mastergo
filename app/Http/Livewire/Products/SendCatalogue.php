<?php

namespace App\Http\Livewire\Products;

use App\Jobs\SendWSCatalogueJob;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class SendCatalogue extends Component
{
    public $clients = [];
    public $clientCode;
    public $selected = [];
    public function mount()
    {
    
        $this->clients = clientWithCode(env('STORE_ID'));
    }

    public function render()
    {
        return view('livewire.products.send-catalogue');
    }

    public function updatedClientCode($value)
    {
        $clients=is_array($this->clients)?$this->clients:$this->clients->toArray();
        
        if($value && array_search($value, array_column($this->selected, 'code')) === false){
            array_push(
                $this->selected,
                [
                    'code' => $this->clientCode,
                    'name' => $clients[$value],
                ]
            );
        }
        $this->clientCode = null;
    }
    public function removeItem($code){
        $this->selected = array_filter($this->selected, function($item) use ($code){
            return $item['code'] !== $code;
        });
    }
    public function sendCatalogue(){
     dispatch(new SendWSCatalogueJob(array_column($this->selected, 'code')))->onConnection('sync');
     $this->emit('showAlert','Se ha enviado el catálogo a los clientes seleccionados','success');
    }
}
