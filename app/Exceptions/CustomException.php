<?php

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{
    public function render($request, Exception $e)
    {       
        dd($e);
        return response()->json(["error" => true, "message" => $this->getMessage()]);       
    }
}
