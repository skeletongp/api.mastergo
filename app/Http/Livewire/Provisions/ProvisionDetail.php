<?php

namespace App\Http\Livewire\Provisions;

use App\Models\Provision;
use Livewire\Component;

class ProvisionDetail extends Component
{
    public $provision_code;
    public $provisions=[];
    protected $listeners=['modalOpened'];

    public function render()
    {
        return view('livewire.provisions.provision-detail');
    }

    public function modalOpened(){

        $place=getPlace();
        $this->provisions =Provision::where('place_id',$place->id)
            ->where('code',$this->provision_code)
            ->with('provisionable','provider')
            ->get() ;
    }
}
