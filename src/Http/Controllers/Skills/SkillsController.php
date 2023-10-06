<?php

namespace MiniRest\Http\Controllers\Skills;

use MiniRest\Http\Response\Response;
use MiniRest\Http\Controllers\Controller;
use MiniRest\Repositories\SkillsRepository;

class SkillsController extends Controller
{
    private SkillsRepository $skills;

    public function __construct()
    {
        $this->skills = new SkillsRepository();
    }

    public function index()
    {
        Response::json(['skills' => $this->skills->getAll()]);
    }
}
