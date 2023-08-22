<?php

namespace MiniRest\Exceptions\Validations;

use Exception;

class InputValidationException extends Exception
{
    public function __construct($fieldName)
    {
        $message = "Campo obrigatório '$fieldName' não pode estar vazio";
        $code = 400;
        parent::__construct($message, $code);
    }
}

