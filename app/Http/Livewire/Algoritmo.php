<?php

namespace App\Http\Livewire;

use App\Http\Traits\Livewire\Confirm;
use Livewire\Component;

class Algoritmo extends Component
{
    use Confirm;

    public $cadena, $result, $recurse=[];
    protected $listeners = [
        'validateAuthorization',
        'comparar',
        'buscar'
    ];
    public function render()
    {
        return view('livewire.algoritmo');
    }
    public function comparar($data)
    {
        $value = $data['value'];
        $this->result = strtolower($this->cadena) == strtolower($value) ?
            "'{$this->cadena}' y '{$value}' son iguales" :
            "'{$this->cadena}' y '{$value}' no son iguales";
       
    }
    public function medir()
    {
        $this->validate([
            'cadena' => 'required'
        ]);
      
        $this->result="La cadena '{$this->cadena}' tiene "
        . strlen($this->cadena) . " caracteres";
      
    }
    public function buscar($data)
    {
        $value = $data['value'];
        $result = strpos(strtolower($this->cadena), strtolower($value));
        $arreglo=explode(" ",$this->cadena);
      
        $this->result= $result ?
            "La cadena '{$this->cadena}' contiene la palabra '{$value}' ".array_count_values($arreglo)[$value]. ' veces ' :
            "La cadena '{$this->cadena}' no contiene la palabra '{$value }' ";
    }
    public function operarString($event, $msg)
    {
        $this->validate([
            'cadena' => 'required'
        ]);
        $this->alert('warning', $msg, [
            'position' => 'center',
            'allowOutsideClick' => false,
            'timer' => null,
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => $event,
            'input' => 'text',
            'inputLabel' => 'Ingrese un valor',
            'showDenyButton' => false,
            'data' => ['value' => 'cadena', 'method' => $event],
            'onDenied' => '',
            'showCancelButton' => true,
            'onDismissed' => '',
            'timerProgressBar' => false,
            'confirmButtonText' => 'Aceptar',
            'cancelButtonText' => 'Cancelar',
        ]);
    }
}
