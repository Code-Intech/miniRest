<?php

namespace MiniRest\Exceptions\Validations;

use Exception;

class EmailValidationException extends Exception
{
    public function __construct($email){
        
        $message = "Insira um e-mail válido!";
        $code = 400;
        parent::__construct($message, $code);
    }
}