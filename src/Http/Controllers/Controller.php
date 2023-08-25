<?php

namespace MiniRest\Http\Controllers;
use Illuminate\Pagination\Paginator;
use MiniRest\Http\Request\Request;
use MiniRest\Http\Response\Response;

class Controller
{

    protected function paginate($query, $perPage = 10)
    {
        $page = Paginator::resolveCurrentPage('page', (new Request())->get('page'));
        $results = $query->paginate($perPage, ['*'], 'page', $page);
        return Response::json($results);
    }
}