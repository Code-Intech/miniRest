<?php

namespace MiniRest\Http\Controllers\Gender;

use MiniRest\Http\Response\Response;
use MiniRest\Models\Gender;
use MiniRest\Repositories\GenderRepository;

class GenderController
{
    public function index()
    {
        Response::json(['genres' => (new GenderRepository())->getAll()]);
    }
}