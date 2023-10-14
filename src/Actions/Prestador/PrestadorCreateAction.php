<?php

namespace MiniRest\Actions\Prestador;

use MiniRest\DTO\Prestador\PrestadorCreateDTO;
use MiniRest\Http\Response\Response;

class PrestadorCreateAction
{
    public function execute(int $userId, PrestadorCreateDTO $prestadorCreateDTO)
    {
        Response::json($prestadorCreateDTO->toArray());
    }
}