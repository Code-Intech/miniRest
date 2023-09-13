<?php

namespace MiniRest\Http\Controllers\Gender;

use MiniRest\Http\Response\Response;
use MiniRest\Models\Gender;

class GenderController
{
    public function index()
    {
        Response::json(['genres' => Gender::all()]);
    }
}