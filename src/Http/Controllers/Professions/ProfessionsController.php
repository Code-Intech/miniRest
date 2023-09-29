<?php

namespace MiniRest\Http\Controllers\Professions;

use MiniRest\Models\Professions;
use MiniRest\Http\Response\Response;
use MiniRest\Http\Controllers\Controller;

class ProfessionsController extends Controller
{
    public function index()
    {
        Response::json(['profession' => $this->paginate(Professions::query())]);
    }
}

?>