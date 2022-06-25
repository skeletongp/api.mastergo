<?php

namespace App\Http\Livewire\Settings\Places;

use App\Models\Place;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class PlaceIndex extends Component
{
    public $places;

    protected $listeners=['reloadPlaces'];
    public function render()
    {
        $this->places=auth()->user()->store->places()->get();
        return view('livewire.settings.places.place-index');
    }

    public function reloadPlaces()
    {
        $this->render();
    }
    public function changePlace($id)
    {
        $user=User::find( auth()->user()->id);
        $user->update(['place_id'=>$id]);
        Cache::forget('place_' . auth()->user()->id);
        return redirect(url()->previous());
    }
}
