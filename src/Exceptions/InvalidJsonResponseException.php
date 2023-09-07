<?php

namespace MiniRest\Exceptions;

use Exception;

class InvalidJsonResponseException extends Exception
{

    public function __construct(string $message = "Json must return a message", int $code = 500)
    {
        parent::__construct($message, $code);
    }

}