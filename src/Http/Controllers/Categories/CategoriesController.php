<?php

namespace MiniRest\Http\Controllers\Categories;

use MiniRest\Models\Categories;
use MiniRest\Http\Response\Response;
use MiniRest\Http\Controllers\Controller;

class CategoriesController extends Controller
{
    public function index()
    {
        Response::json(['category' => $this->paginate(Categories::query())]);
    }
}

?>