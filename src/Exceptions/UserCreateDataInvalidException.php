<?php

namespace MiniRest\Exceptions;

class UserCreateDataInvalidException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message, 400);
    }
}