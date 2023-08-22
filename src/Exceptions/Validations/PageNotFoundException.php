<?php

namespace MiniRest\Exceptions\Validations;

use Exception;

class PageNotFoundException extends Exception
{
    public function __construct($url){
        $message = "Página não encontrada!";
        $code = 404;

        parent::__construct($message, $url);
    }
}