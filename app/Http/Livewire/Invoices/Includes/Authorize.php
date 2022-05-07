<?php

namespace App\Http\Livewire\Invoices\Includes;

use App\Models\Client;
use Illuminate\Support\Facades\Hash;

trait Authorize
{

    public $hashedPassword, $unhashedPassword;

    public function authorizeAction()
    {
        if (Hash::check($this->unhashedPassword, $this->hashedPassword)) {
            $this->confirmedAddItems();
            $this->open=false;
        } else {
           $this->emit('showAlert','Datos no válidos','error');
        }
    }
}
