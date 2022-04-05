<?php

namespace App\Http\Livewire\Settings\Places;

use Livewire\Component;

class PlaceIndex extends Component
{
    public $places;

    protected $listeners=['reloadPlaces'];
    public function render()
    {
        $this->places=auth()->user()->store->places;
        return view('livewire.settings.places.place-index');
    }

    public function reloadPlaces()
    {
        $this->render();
    }
    public function changePlace($id)
    {
        request()->session()->put('place_id', $id);
        return redirect(url()->previous());
    }
}
