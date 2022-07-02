<?php

namespace App\Http\Livewire\Cheques;

use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ChequeList extends LivewireDatatable
{
    public $padding = "px-2";
    public $headTitle = "Lista de Cheques";
    public function builder()
    {
        $place = auth()->user()->place;
        $cheques = $place->cheques()->with('user', 'bank', 'chequeable');
        return $cheques;
    }

    public function columns()
    {
        $cheques = $this->builder()->get()->toArray();
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
            })->label('Emitido Por'),
            Column::callback(['chequeable_id', 'id'], function ($cheque, $id) use ($cheques) {
                $result = arrayFind($cheques, 'id', $id);
                if ($result['type'] == 'Recibido') {
                    return $result['user']['fullname'];
                }
                return $this->getChequetableName($result['chequeable']);
            })->label('Destino'),
            

            Column::callback(['bank_id', 'id'], function ($bank_id, $id) use ($cheques) {
                $result = arrayFind($cheques, 'id', $id);
                return $result['bank']['bank_name'] . '<br>' . $result['bank']['bank_number'];
            })->label('Banco'),
            Column::name('type')->label('Tipo')->filterable([
                'Emitido', 'Recibido',
            ]),
            Column::callback('status', function ($status) {
                return $status == 'Pago' ? '<span class="text-green-400">Pagado</span>' : '<span class=" text-red-400">Pendiente</span>';
            })->label('Estado')->filterable(['Pendiente', 'Pago', 'Cancelado']),
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
