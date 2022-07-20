<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Invoice;
use App\Models\Outcome;
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
    
        $incomes = $place->payments()->where('payable_type',Invoice::class)->whereDate('created_at', '>=', Carbon::now()->subWeek())
            ->groupBy('day')
            ->orderBy('day', 'DESC')
            ->get(array(
                DB::raw('Date(day) as date'),
                DB::raw('sum(payed-cambio) as "amount"')
            ))->pluck('amount', 'date')->toArray();
        $outcomes =  $place->payments()->where('payable_type',Outcome::class)->whereDate('created_at', '>=', Carbon::now()->subWeek())
        ->groupBy('day')
        ->orderBy('day', 'DESC')
        ->get(array(
            DB::raw('Date(day) as date'),
            DB::raw('sum(payed-cambio) as "amount"')
        ))->pluck('amount', 'date')->toArray();
        $this->setData($incomes, $outcomes);
       
    }
    public function setData($incomes, $outcomes)
    {
        foreach ($incomes as $key => $value) {
            if (empty($outcomes[$key])) {
                $outcomes[$key] = 0;
            }
        }
        foreach ($outcomes as $key => $value) {
            if (empty($incomes[$key])) {
                $incomes[$key] = 0;
            }
        }
        krsort($outcomes);
        krsort($incomes);
        
        $this->data = [
            'labelsIncomes' => array_keys($incomes),
            'valuesIncomes' => array_values($incomes),
            'labelsOutcomes' => array_keys($outcomes),
            'valuesOutcomes' => array_values($outcomes),
        ];
    }
}
