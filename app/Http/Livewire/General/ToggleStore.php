<?php

namespace App\Http\Livewire\General;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ToggleStore extends Component
{

    public $stores, $store_id, $label=true, $title=false;

    protected $listeners=['reloadToggleStore'];
    public function mount()
    {
        $this->stores=auth()->user()->stores;
        $this->store_id=auth()->user()->store->id;
    }
    public function render()
    {
        return view('livewire.general.toggle-store');
    }
    public function updatedStoreId()
    {
        $store=Store::where('id',$this->store_id)->first();
        Cache::put('store_'.auth()->user()->id, $store);
        Cache::forget('place_'.auth()->user()->id);
        return redirect(url()->previous());
    }
    public function reloadToggleStore()
    {
       $this->render();
    }
}
