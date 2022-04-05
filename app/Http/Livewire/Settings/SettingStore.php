<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;

class SettingStore extends Component
{

    protected $listeners = ['reloadSettingStore'];

    public function render()
    {
        return view('livewire.settings.setting-store');
    }

    public function reloadSettingStore()
    {
        $this->mount();
    }
}
