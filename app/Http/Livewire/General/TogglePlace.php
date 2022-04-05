<?php

namespace App\Http\Livewire\General;

use Livewire\Component;

class TogglePlace extends Component
{
    public $place_id, $places;
    public function render()
    {
        $this->places=auth()->user()->places->pluck('name','id');
        $this->place_id=session('place_id');
        return view('livewire.general.toggle-place');
    }

    public function updatedPlaceId()
    {
        request()->session()->put('place_id', $this->place_id);
       
        return redirect(url()->previous());
    }
}
