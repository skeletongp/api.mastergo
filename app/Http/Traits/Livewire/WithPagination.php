<?php

namespace App\Http\Traits\Livewire;

use Illuminate\Http\Request;
use Livewire\WithPagination as OriginalPagination;

trait WithPagination
{
    use OriginalPagination;

   /*  public function getQueryString()
    {
        dd(request()->query->add([]));
        return array_merge(['page' => ['except' => 1]], $this->queryString);
    } */
}
