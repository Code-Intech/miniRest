<?php

namespace MiniRest\Exceptions\Permissions;

use Exception;

class PremiumValidationException extends Exception
{
    public function __construct()
    {
        $message = "Torne-se Premium para acessar este recurso!";
        $code = 403;
        parent::__construct($message, $code);
    }
}