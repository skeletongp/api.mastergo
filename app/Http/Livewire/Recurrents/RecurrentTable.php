<?php

namespace App\Http\Livewire\Recurrents;

use App\Models\Recurrent;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Classes\Column;use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class RecurrentTable extends LivewireDatatable
{
    public $headTitle = "Obligaciones recurrentes";
    public $padding = 'px-2';
    use AuthorizesRequests;
    public function builder()
    {
        $place = auth()->user()->place;
        $recurrents = $place->recurrents()->with('count');
        return $recurrents;
    }

    public function columns()
    {
        $recurrents = $this->builder()->get()->toArray();
        return [
            Column::name('name')->label('Nombre'),
            Column::callback('amount', function ($amount) {
                return '$' . formatNumber($amount);
            })->label('Monto')->enableSummary(),
            Column::name('recurrency')->label('Recurrencia'),
            Column::callback(['count_id', 'id'], function ($count, $id) use ($recurrents) {
                $result = arrayFind($recurrents, 'id', $id);
                return $result['count']['name'];
            })->label('Cuenta'),
            DateColumn::name('expires_at')->label('Vencimiento'),
            Column::callback(['created_at', 'id'], function ($created_at, $id) use ($recurrents) {
                $recurrent = arrayFind($recurrents, 'id', $id);
                return view('pages.recurrents.actions', compact('recurrent'));
            })->label(''),
            Column::delete()->label(''),
        ];
    }
    public function delete($id)
    {
        $this->authorize('Borrar Obligaciones');
        $recurrent = Recurrent::find($id);
        $recurrent->delete();
        $this->emit('refreshLivewireDatatable');
    }
    public function summarize($column)
    {
        $results = json_decode(json_encode($this->results->items()), true);
        foreach ($results as $key => $value) {
            $val = json_decode(json_encode($value), true);
            $results[$key][$column] = preg_replace("/[^0-9 .]/", '', $val[$column]);
        }
        try {
            foreach ($results as $key => $result) {;
                $results[$key][$column] = $this->getTotalFromResult($result[$column], $result['recurrency']);
            }
            return "<h1 class='font-bold text-right'>" . '$' . formatNumber(array_sum(array_column($results, $column))) . "</h1>";;
        } catch (\TypeError $e) {
            return '';
        }
    }
    public function getTotalFromResult($amount, $recurrency)
    {
        switch ($recurrency) {
            case 'Semanal':
                return $amount * 4;
                break;
            case 'Quincenal':
                return $amount * 2;
                break;
            case 'Mensual':
                return $amount * 1;
                break;
            case 'Anual':
                return $amount / 12;
                break;
            default:
                return $amount;
                break;
        }
    }
}
