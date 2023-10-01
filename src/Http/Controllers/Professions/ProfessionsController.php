<?php

namespace MiniRest\Http\Controllers\Professions;

use MiniRest\Repositories\ProfessionsRepository;
use MiniRest\Http\Response\Response;
use MiniRest\Http\Controllers\Controller;

class ProfessionsController extends Controller
{
    private ProfessionsRepository $professions;

    public function __construct()
    {
        $this->professions = new ProfessionsRepository();
    }

    public function index()
    {
        Response::json(['profession' => $this->professions->getAll()]);
    }
}

?>