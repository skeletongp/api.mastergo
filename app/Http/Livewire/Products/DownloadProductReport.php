<?php

namespace App\Http\Livewire\Products;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DownloadProductReport extends Component
{
    public function render()
    {
        return view('livewire.products.download-product-report');
    }
    public function getReport(){
        return $this->download();
       // return redirect()->route('products.index');
    }
    public function download()
    {
       
    }
}
