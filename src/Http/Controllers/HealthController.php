<?php

namespace MiniRest\Http\Controllers;

use MiniRest\Http\Response\Response;

class HealthController
{
    public function health(): void
    {
        Response::json(["message" => "ok"]);
    }
}