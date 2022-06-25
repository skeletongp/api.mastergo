<?php

namespace App\Http\Livewire\Condiments;

use App\Http\Livewire\Products\Includes\SumProductTrait;
use Livewire\Component;

class SumCondiment extends Component
{
    use SumProductTrait;
    public $recursos, $brands, $count_code, $ref='N/D', $counts, $provider_id;
    public $efectivo = 0, $tarjeta = 0, $transferencia = 0, $banks, $bank_id, $ref_bank, $tax=0, $discount=0;
    public $recurso, $cant, $brand_id, $recurso_id;
    public $selected = [], $setCost=false, $total=0, $hideButton=true;

    protected $rules=[
        'selected'=>'required|min:1',
        'provider_id'=>'required|min:1',
    ];
    public function render()
    {
        return view('livewire.condiments.sum-condiment');
    }
}
