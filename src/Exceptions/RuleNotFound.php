<?php

namespace MiniRest\Exceptions;

use MiniRest\Helpers\StatusCode\StatusCode;

class RuleNotFound extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message, StatusCode::NOT_FOUND);
    }
}