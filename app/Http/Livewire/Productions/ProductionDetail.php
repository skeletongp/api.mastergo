<?php

namespace App\Http\Livewire\Productions;

use App\Models\Product;
use Livewire\Component;

class ProductionDetail extends Component
{
    public $production, $data;
    function mount($production)
    {
        $this->production = $production;
    }
    public function render()
    {
        $this->setData();
        return view('livewire.productions.production-detail');
    }
    public function setData()
    {
        $production = $this->production;
        $results=$production->products()
        ->with('productible')
        ->groupBy('productible_id','productible_type')
        ->selectRaw('sum(cant) as cant, productible_id, productible_type')
        ->get()
        ->pluck('cant','productible.name')
        ->toArray();
        krsort($results);
        $this->data = [
            'labelResults' => array_keys($results),
            'valueResults' => array_values($results),
        ];
    }
    function _group_by($array, $key) {
        $return = array();
        foreach($array as $val) {
            $return[$val[$key]][] = $val;
        }
        return $return;
    }
}
