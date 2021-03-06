<?php

namespace App\Http\Livewire\General;

use App\Models\Place;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class TogglePlace extends Component
{
    public $place_id, $places;
    public function render()
    {
        $this->places = auth()->user()->places->pluck('name', 'id');
        return view('livewire.general.toggle-place');
    }

    public function updatedPlaceId()
    {
        $place = Place::where('id', $this->place_id)->first();
        Cache::forget('place_' . auth()->user()->id);
        Cache::put('place_' . auth()->user()->id, $place);
        User::find(auth()->user()->id)->update(['place_id'=>$place->id]);
        return redirect()->route('home');
    }
}
