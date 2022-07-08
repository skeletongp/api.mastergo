<?php

namespace App\Http\Livewire\Finances;

use Livewire\Component;

class FinancesIndex extends Component
{
    public $componentName = 'banks.bank-list';

    protected $queryString = ['componentName'];

    public function render()
    {
        return view('livewire.finances.finances-index');
    }
    public function changeView($componentName)
    {
        $this->componentName = $componentName;
    }
}
