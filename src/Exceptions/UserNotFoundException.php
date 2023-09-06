<?php

namespace MiniRest\Exceptions;

use MiniRest\Helpers\StatusCode\StatusCode;

class UserNotFoundException extends \Exception
{
    public function __construct(string $message = 'Acesso não autorizado')
    {
        parent::__construct($message, StatusCode::ACCESS_NOT_ALLOWED);
    }
}