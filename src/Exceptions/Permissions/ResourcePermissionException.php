<?php

namespace MiniRest\Exceptions\Permissions;

use Exception;

class ResourcePermissionException extends Exception
{
    public function __construct($resource)
    {
        $message = "Acesso negado! Você não tem permissão para acessar '$resource' ";
        $code = 403;
        parent::__construct($message, $code);
    }
}

