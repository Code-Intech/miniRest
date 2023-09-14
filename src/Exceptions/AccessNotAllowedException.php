<?php

namespace MiniRest\Exceptions;

use MiniRest\Helpers\StatusCode\StatusCode;

class AccessNotAllowedException extends \Exception
{
    public function __construct(string $message = 'Access not allowed')
    {
        parent::__construct($message, StatusCode::ACCESS_NOT_ALLOWED);
    }
}