<?php
namespace App\Http\Classes;

use Mediconesystems\LivewireDatatables\NumberColumn as LivewireDatatablesNumberColumn;

class NumberColumn extends LivewireDatatablesNumberColumn{

    public function formatear(String $format = 'number', $style=null): self
    {
        switch ($format) {
            case 'money':
                $this->callback = function ($value) use ( $style) {
                    return '<span class="'.$style.'">$'. formatNumber($value).'</span>';
                };
                break;
            case 'number':
                $this->callback = function ($value) use ( $style) {
                    return  '<span class="'.$style.'">'.formatNumber($value).'</span>';
                };
                break;
                
            default:
                # code...
                break;
        }
        
        return $this;
    }
 
}