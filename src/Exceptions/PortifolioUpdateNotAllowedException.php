<?php

namespace MiniRest\Exceptions;

use MiniRest\Helpers\StatusCode\StatusCode;

class PortifolioUpdateNotAllowedException extends \Exception
{
    public function __construct(string $message = 'Você não possui autorização para atualizar/deletar este portifólio ou fotos derivadas dele')
    {
        parent::__construct($message, StatusCode::ACCESS_NOT_ALLOWED);
    }
}