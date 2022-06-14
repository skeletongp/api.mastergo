<?php

namespace App\Traits;

trait ModelContableTrait{

    private static $model;

    public static function setContable($id, $code, $origin, $name=null)
    {
        $model=self::$model;
        dd(self::$model);
        $model=$model->find($id);
        if (!$name) {
            $name=$model->name;
        }
       setContable($model, $code, $origin, $name);
    }
}