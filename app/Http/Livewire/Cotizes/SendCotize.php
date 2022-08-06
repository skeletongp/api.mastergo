<?php

namespace App\Http\Livewire\Cotizes;

use App\Mail\CotizeMail;
use App\Models\Cotize;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class SendCotize extends Component
{
    public $cotize;
    public function render()
    {
        return view('livewire.cotizes.send-cotize');
    }

    public function sendCotize(){
        $cotize=Cotize::find($this->cotize)->load('details','details.product','client','user','place','pdf');
        Mail::to([$cotize->client->email, $cotize->store->email])->send(new CotizeMail($cotize));
        sendInvoiceWS($cotize->pdf->pathLetter, $cotize->client->contact->cellphone, $cotize->id);
        $this->emit('showAlert','Cotización enviada correctamente','success');
     } 
}
