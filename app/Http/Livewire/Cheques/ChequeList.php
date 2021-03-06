<?php

namespace App\Http\Livewire\Cheques;

use Illuminate\Support\Facades\DB;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ChequeList extends LivewireDatatable
{
    public $padding = "px-2 whitespace-nowrap";
    public $headTitle = "Lista de Cheques";
    public $hideable = 'select';
    public function builder()
    {
        $place = auth()->user()->place;
        $cheques = $place->cheques()->with('user', 'bank', 'chequeable')->orderBY('created_at', 'desc');
        return $cheques;
    }

    public function columns()
    {
        $cheques = $this->builder()->get()->toArray();
        $store = auth()->user()->store;
        $banks = $store->banks()->selectRaw("CONCAT(bank_name,' - ',currency) as name, id")->pluck('name', 'id')->toArray();
        foreach ($banks as $key => $value) {
            $banks[$key] = [
                'name' => $value,
                'id' => $key
            ];
        }
        return [
            Column::name('reference')->label('Referencia'),
            Column::callback('amount', function ($amount) {
                return '$' . formatNumber($amount);
            })->label('Monto'),

            Column::callback(['user_id', 'id'], function ($user, $id) use ($cheques) {
                $result = arrayFind($cheques, 'id', $id);
                if ($result['type'] == 'Emitido') {
                    return $result['user']['fullname'];
                }
                return $this->getChequetableName($result['chequeable']);
            })->label('Emisor'),
            Column::callback(['chequeable_id', 'id'], function ($cheque, $id) use ($cheques) {
                $result = arrayFind($cheques, 'id', $id);
                if ($result['type'] == 'Recibido') {
                    return $result['bank']['bank_name'];
                }
                return $this->getChequetableName($result['chequeable']);
            })->label('Destino'),


            Column::callback(['bank_id', 'id'], function ($bank_id, $id) use ($cheques) {
                $result = arrayFind($cheques, 'id', $id);
                return ellipsis($result['bank']['bank_name'], 20);
            })->label('Banco')->filterable($banks),
            Column::name('type')->label('Tipo')->filterable([
                'Emitido', 'Recibido',
            ]),
            Column::callback('status', function ($status) {
                return $status == 'Pago' ? '<span class="text-green-500 font-semibold uppercase">Pagado</span>' : ($status=='Pendiente' ? '<span class="text-orange-500 font-semibold uppercase">Pendiente</span>' : '<span class="text-red-500 font-semibold uppercase">Anulado</span>');
            })->label('Estado')->filterable(['Pendiente', 'Pago', 'Anulado']),
            Column::callback(['status','id'], function ($status, $id) use ($cheques) {
                $cheque=arrayFind($cheques, 'id', $id);
                return view('pages.cheques.actions', compact('cheque'));
            })->label('Acci??n')->contentAlignCenter(),
        ];
    }
    function getChequetableName(array $result)
    {
        if (array_key_exists('fullname', $result)) {
            return $result['fullname'];
        } else {
            return $result['name'];
        }
    }
}
