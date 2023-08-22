<?php

namespace MiniRest\Exceptions\Validations;

use Exception;

class TokenValidationException extends Exception
{
    public function __construct()
    {
        $message = "Código de verificação inválido!";
        $code = 400;
        parent::__construct($message, $code);
    }
}

