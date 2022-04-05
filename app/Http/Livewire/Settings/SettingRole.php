<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;

class SettingRole extends Component
{
    protected $listeners=['reloadRolesIndex'];
    public function render()
    {
        return view('livewire.settings.setting-role');
    }
    public function reloadRolesIndex()
    {
        $this->render();
    }
   
}
