<?php
namespace App\Http\Livewire;

use Carbon\Carbon;

trait UniqueDateTrait
{
    public $uniqueDate = true;
    public function doDateFilterStart($index, $start)
    {
        $this->activeDateFilters[$index]['start'] = $start;
        $end=Carbon::parse($start)->addDay()->format('Y-m-d');
        $this->activeDateFilters[$index]['end'] = $end;
        $this->setPage(1);
        $this->setSessionStoredFilters();
    }
}