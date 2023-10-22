<?php

namespace MiniRest\Exceptions;

use MiniRest\Helpers\StatusCode\StatusCode;

class PrestadorNotFoundException extends \Exception
{
    public function __construct(string $message = 'Prestador não existe')
    {
        parent::__construct($message, StatusCode::NOT_FOUND);
    }
}