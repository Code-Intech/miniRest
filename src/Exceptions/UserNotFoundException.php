<?php

namespace MiniRest\Exceptions;

class UserNotFoundException extends \Exception
{
    public function __construct(string $message = 'Acesso não autorizado')
    {
        parent::__construct($message, 401);
    }
}