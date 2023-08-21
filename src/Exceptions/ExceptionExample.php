<?php

namespace MiniRest\Exceptions;

use Exception;

class ExceptionExample extends Exception
{
    public function __construct($message, $code = 0)
    {
        parent::__construct($message, $code);

    }
}