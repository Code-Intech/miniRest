<?php

namespace MiniRest\Exceptions\Validations;

use Exception;

class PasswordValidationException extends Exception
{
    public function __construct()
    {
        $message = "A senha não atende aos critérios, insira outra senha!";
        $code = 400;
        parent::__construct($message, $code);
    }
}