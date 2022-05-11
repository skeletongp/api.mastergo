<?php

namespace App\Http\Livewire\General;

use App\Models\Client;
use Illuminate\Support\Facades\Hash;

trait Authorize
{

    public $hashedPassword, $unhashedPassword;

    public function authorizeAction($action)
    {
        $this->validate([
            'unhashedPassword'=>'required'
        ]);
        if (Hash::check($this->unhashedPassword, $this->hashedPassword)) {
            $this->reset('unhashedPassword','hashedPassword');
            $this->emit($action);
            $this->emit('openAuthorize');
            $this->open=false;
        } else {
           $this->emit('showAlert','Datos no válidos','error');
        }
    }
}
