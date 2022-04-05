<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;

class SettingIndex extends Component
{
    public $componentName = 'settings.setting-user';

    protected $queryString = ['componentName'];

    public function render()
    {
        return view('livewire.settings.setting-index');
    }
    public function changeView($componentName)
    {
        $this->componentName = $componentName;
    }
}
