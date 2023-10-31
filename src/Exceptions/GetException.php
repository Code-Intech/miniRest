<?php

namespace MiniRest\Exceptions;

use MiniRest\Helpers\StatusCode\StatusCode;

class GetException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message, StatusCode::SERVER_ERROR);
    }
}

?>
