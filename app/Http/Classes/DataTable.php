<?php

namespace App\Http\Classes;

use Illuminate\Support\Facades\DB;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class DataTable extends LivewireDatatable
{
    public $sortField;
    public function sort($index, $direction = null)
    {
        if (! in_array($direction, [null, 'asc', 'desc'])) {
            throw new \Exception("Invalid direction $direction given in sort() method. Allowed values: asc, desc.");
        }

        if ($this->sort === (int) $index) {
            if ($direction === null) { // toggle direction
                $this->direction = ! $this->direction;
            } else {
                $this->direction = $direction === 'asc' ? true : false;
            }
        } else {
            $this->sort = (int) $index;
        }
        if ($direction !== null) {
            $this->direction = $direction === 'asc' ? true : false;
        }
        $this->setPage(1);

        session()->put([
            $this->sessionStorageKey() . '_sort' => $this->sort,
            $this->sessionStorageKey() . '_direction' => $this->direction,
        ]); 
        $this->sortField = $this->columns[$this->sort]['name'];

    }
    
}
