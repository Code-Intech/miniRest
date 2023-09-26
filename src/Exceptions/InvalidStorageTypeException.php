<?php

namespace MiniRest\Exceptions;

use MiniRest\Helpers\StatusCode\StatusCode;

class InvalidStorageTypeException extends \Exception
{
    public function __construct(string $message = "", int $code = StatusCode::SERVER_ERROR)
    {
        parent::__construct($message, $code);
    }
}