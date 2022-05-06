<?php

namespace App\Http\Livewire\Dashboard;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StatWeek extends Component
{
    public $data;
    public function render()
    {
        $this->balanceWeek();
        return view('livewire.dashboard.stat-week');
    }
    public function balanceWeek()
    {
        $place = auth()->user()->place;
        $incomes = $place->incomes()->where('created_at', '>=', Carbon::now()->subWeek())
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->get(array(
                DB::raw('Date(created_at) as date'),
                DB::raw('sum(amount) as "amount"')
            ))->pluck('amount','date')->toArray();
        $outcomes = $place->outcomes()->where('created_at', '>=', Carbon::now()->subWeek())
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->get(array(
                DB::raw('Date(created_at) as date'),
                DB::raw('sum(amount) as "amount"')
            ))->pluck('amount','date')->toArray();
        $this->data = [
            'labelsIncomes' => array_keys($incomes),
            'valuesIncomes' =>array_values($incomes),
            'labelsOutcomes' => array_keys($outcomes),
            'valuesOutcomes' =>array_values($outcomes),
        ];
    }
}
