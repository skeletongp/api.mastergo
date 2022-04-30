<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;

class StatCard extends Component
{
    public $icon, $title, $value;
    public function render()
    {
        return view('livewire.dashboard.stat-card');
    }
}
