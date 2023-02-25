<?php

namespace App\Http\Livewire\Provisions;

use App\Models\Provision;
use Livewire\Component;

class PrintProvision extends Component
{
    public $provision_code;


    public function render()
    {
        return view('livewire.provisions.print-provision');
    }

    public function print()
    {
        $provisions=Provision::whereCode($this->provision_code)
        ->with('provider','provisionable','atribuible', 'place.store','place.preference', 'user')
        ->get();

        $this->emit('printProvision',$provisions);

    }
}
