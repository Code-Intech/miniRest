<?php

namespace MiniRest\Exceptions;

use MiniRest\Helpers\StatusCode\StatusCode;

class UploadErrorException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message, StatusCode::REQUEST_ERROR);
    }
}