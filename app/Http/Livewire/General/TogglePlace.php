<?php

namespace App\Http\Livewire\General;

use App\Models\Place;
use Illuminate\Support\Facades\Cache;
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
        $place=Place::where('id',$this->place_id)->first();
        Cache::put('place_'.auth()->user()->id, $place);
        return redirect(url()->previous());
    }
}
