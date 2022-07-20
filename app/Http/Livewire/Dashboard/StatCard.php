<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Invoice;
use App\Models\Outcome;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StatCard extends Component
{
    public $data;
    public function render()
    {
        $this->incomeChart();
        return view('livewire.dashboard.stat-card');
    }
    public function incomeChart()
    {
        $place=auth()->user()->place;
        $incomes=$place->payments()->whereDate('created_at', date('Y-m-d'))->where('payable_type',Invoice::class)->sum(DB::raw('payed-cambio'));
        $outcomes=$place->payments()->whereDate('created_at', date('Y-m-d'))->where('payable_type',Outcome::class)->sum(DB::raw('payed-cambio'));;
        $this->data=[
            'labels'=>['Ingresos', 'Gastos'],
            'values'=>[$incomes, $outcomes]
        ];
        
    }
}
