<?php

namespace MiniRest\Controllers;
use MiniRest\Http\Request;
use MiniRest\Http\Response;

class ExampleController
{
    public function index()
    {
        Response::json(['teste' => 'teste']);
    }
    public function teste()
    {
        Response::json(['test1' => 'rear']);
    }

    /**
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $nome = $request->json('nome');
        $idade = $request->json('idade');
        $testeGet = $request->get('teste');

        Response::json(['pessoa' => [
            'nome' => $nome,
            'idade' => $idade,
            'testeGet' => $testeGet
        ]]);
    }
}