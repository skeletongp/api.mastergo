<?php

namespace App\Http\Livewire\Productions;

use App\Models\Proceso;
use App\Models\Production;
use Livewire\Component;

class EditProduction extends Component
{
    public $production;
    public  $status = false, $labelStatus = 'Iniciado';

    protected $rules = [
        'production' => 'required',
        'production.setted' => 'required',
        'production.getted' => 'required',
    ];
    public function render()
    {
        if($this->production['status']=='Completado'){

            $this->status = $this->production['status'];
        }
        return view('livewire.productions.edit-production');
    }
    public function updatedStatus()
    {
        $proceso = Proceso::whereId($this->production['proceso_id'])->with('productions')->first();
        $openProd = $proceso->productions()->where('status', '!=', 'Completado')->first();
        if ($openProd && $openProd->id != $this->production['id']) {
            $this->emit('showAlert', 'No puedes cambiar el estado de este proceso porque hay otro en curso', 'warning');
            $this->status=$this->production['status'];
        }
        if ($this->status) {
            $this->production['status'] = 'Completado';
        } else {
            $this->production['status'] = null;
        }
       
    }
   
    public function updateProduction()
    {
        $production = Production::whereId($this->production['id'])->first();

        $date = null;
        if ($this->production['status'] == 'Completado') {
            $date = date(now());
        }
        $production->update([
            'setted' => $this->production['setted'],
            'getted' => $this->production['getted'],
            'status' => $this->status ? 'Completado' : 'Iniciado',
            'end_at' => $date,
            'eficiency' => ($this->production['getted'] / $this->production['expected']) * 100
        ]);
        $place=auth()->user()->place;
        $debitable=$place->findCount('500-03');
        $creditable=$place->findCount('104-04');
        $cost=($production->expected-$production->getted)*$production->costUnit;
        $merma=$cost;
        setTransaction('Reg. Merma en producción',date('YmdH'),$merma, $debitable, $creditable, 'Terminar Procesos');
        $this->emit('showAlert', 'Registro actualizado exitosamente', 'success');
        $this->emit('refreshLivewireDatatable');
    }
}
