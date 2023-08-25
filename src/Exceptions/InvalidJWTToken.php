<?php

namespace MiniRest\Exceptions;

class InvalidJWTToken extends \Exception
{
    public function __construct(string $message = 'Token inválido')
    {
        parent::__construct($message, 401);
    }
}
