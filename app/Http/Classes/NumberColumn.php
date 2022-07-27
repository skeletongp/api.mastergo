<?php
namespace App\Http\Classes;

use Mediconesystems\LivewireDatatables\NumberColumn as LivewireDatatablesNumberColumn;

class NumberColumn extends LivewireDatatablesNumberColumn{

    public function formatear(String $format = 'number', $color= null): self
    {
        switch ($format) {
            case 'money':
                $this->callback = function ($value) {
                    return '$' . formatNumber($value);
                };
                break;
            case 'number':
                $this->callback = function ($value) {
                    return formatNumber($value);
                };
                break;
            case 'bold':
                $this->callback = function ($value) {
                    return '<b>' . $value . '</b>';
                };
                break;
            case 'italic':
                $this->callback = function ($value) {
                    return '<i>' . $value . '</i>';
                };
                break;
                
            default:
                # code...
                break;
        }
        if ($color){
            $this->callback = function ($value) use ($color) {
                return '<span class="'.$color.'">' . $value . '</span>';
            };
        }
        return $this;
    }
 
}