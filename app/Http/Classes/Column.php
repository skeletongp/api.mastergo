<?php
namespace App\Http\Classes;

use Mediconesystems\LivewireDatatables\Column as LivewireDatatablesColumn;
use Closure;
class Column extends LivewireDatatablesColumn{
    public static function callback(
        array|string $columns,
        Closure|string $callback,
        array $params = [],
        ?string $callbackName = null
    ) {
        $column = new static;

        $column->name = 'callback_' . ($callbackName ?? crc32(json_encode(func_get_args())));
        $column->callback = $callback;
        $column->additionalSelects = is_array($columns) ? $columns : array_map('trim', explode(',', $columns));
        $column->params = $params;
        $column->sortable=false;
        return $column;
    }

}