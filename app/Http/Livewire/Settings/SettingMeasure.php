<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;

class SettingMeasure extends Component
{
    public $isUnit=true;

    protected $queryString=['isUnit'];

    public function render()
    {
        return view('livewire.settings.setting-measure');
    }

    public function changeView()
    {
        $this->isUnit=! $this->isUnit;
    }
}
