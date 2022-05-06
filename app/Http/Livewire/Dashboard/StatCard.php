<?php

namespace App\Http\Livewire\Dashboard;

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
        $incomes=$place->incomes()->whereDate('created_at', date('Y-m-d'))->sum('amount');
        $outcomes=$place->outcomes()->whereDate('created_at', date('Y-m-d'))->sum('amount');
        $this->data=[
            'labels'=>['Ingresos', 'Gastos'],
            'values'=>[$incomes, $outcomes]
        ];
        
    }
}
