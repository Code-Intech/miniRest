<?php

namespace MiniRest\Exceptions;

use MiniRest\Helpers\StatusCode\StatusCode;

class InvalidJWTToken extends \Exception
{
    public function __construct(string $message = 'Token inválido')
    {
        parent::__construct($message, StatusCode::ACCESS_NOT_ALLOWED);
    }
}
