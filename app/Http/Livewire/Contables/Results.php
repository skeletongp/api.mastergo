<?php

namespace App\Http\Livewire\Contables;

use Carbon\Carbon;
use Livewire\Component;

class Results extends Component
{
    public $data;
    public function mount()
    {
      
        
    }
    public function render()
    {
        return view('livewire.contables.results');
    }
}
