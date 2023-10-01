<?php

namespace MiniRest\Http\Controllers\Categories;

use MiniRest\Repositories\CategoriesRepository;
use MiniRest\Http\Response\Response;
use MiniRest\Http\Controllers\Controller;

class CategoriesController extends Controller
{
    private CategoriesRepository $categories;

    public function __construct()
    {
        $this->categories = new CategoriesRepository();
    }

    public function index()
    {
        Response::json(['category' => $this->categories->getAll()]);
    }
}

?>