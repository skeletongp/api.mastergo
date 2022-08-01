<?php

namespace App\Http\Livewire\Cheques;

use App\Http\Classes\NumberColumn;
use App\Models\Cheque;
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
        $cheques = 
        Cheque::where('cheques.place_id', $place->id)
        ->where('cheques.store_id', env('STORE_ID'))
        ->leftjoin('moso_master.users', 'users.id', '=', 'cheques.user_id')
        ->leftjoin('banks', 'banks.id', '=', 'cheques.bank_id')
      ->select('cheques.*','users.fullname as user_name','banks.bank_name as bank_name')
        ->with('chequeable')->orderBY('cheques.created_at', 'desc');
        return $cheques;
    }

    public function columns()
    {
        $store = auth()->user()->store;
        $banks = $store->banks()->selectRaw("CONCAT(bank_name,' - ',currency) as name, id")->pluck('name', 'id')->toArray();
        foreach ($banks as $key => $value) {
            $banks[$key] = [
                'name' => $value,
                'id' => $key
            ];
        }
        return [
            Column::name('reference')->label('Ref.'),
            NumberColumn::name('amount')->label('Monto')->formatear('money'),
            Column::name('users.fullname')->label('Usuario'),
            Column::name('banks.bank_name')->label('Banco'),
            Column::name('type')->label('Tipo')->filterable([
                'Emitido', 'Recibido',
            ]),
            Column::callback('status', function ($status) {
                return $status == 'Pago' ? '<span class="text-green-500 font-semibold uppercase">Pagado</span>' : ($status=='Pendiente' ? '<span class="text-orange-500 font-semibold uppercase">Pendiente</span>' : '<span class="text-red-500 font-semibold uppercase">Anulado</span>');
            })->label('Estado')->filterable(['Pendiente', 'Pago', 'Anulado']),
            Column::callback(['status','type','id'], function ($status, $type, $id){
                if($status == 'Pendiente'){
                    return view('pages.cheques.actions', compact('id','type'));
                }
                return "<span class='fas fa-ban text-red-400'></span>";
            })->label('Acción')->contentAlignCenter(), 
           /* 

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
                    return ellipsis($result['bank']['bank_name'],18);
                }
                return $this->getChequetableName($result['chequeable']);
            })->label('Destino'),


            Column::callback(['bank_id', 'id'], function ($bank_id, $id) use ($cheques) {
                $result = arrayFind($cheques, 'id', $id);
                return ellipsis($result['bank']['bank_name'], 20);
            })->label('Banco')->filterable($banks),
           
            
           */
        ];
    }
    function getChequetableName(array $result)
    {
        if (array_key_exists('fullname', $result)) {
            return ellipsis($result['fullname'],18);
        } else {
            return ellipsis($result['name'],18);
        }
    }
}
