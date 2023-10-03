<?php

namespace MiniRest\Http\Controllers\Gender;

use MiniRest\Http\Response\Response;
use MiniRest\Repositories\GenderRepository;

class GenderController
{
    public function index(): void
    {
        Response::json(['genres' => (new GenderRepository())->getAll()]);
    }
}