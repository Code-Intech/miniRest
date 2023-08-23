<?php

namespace MiniRest\Http\Controllers;
use MiniRest\Http\Request\Request;
use MiniRest\Http\Request\RequestValidation\RequestValidator;
use MiniRest\Http\Response\Response;
use MiniRest\Models\User;

class ExampleController
{
    public function index()
    {
        Response::json(['user' => User::all()]);
    }
    public function teste()
    {
        Response::json(['test1' => 'teste']);
    }

    /**
     * @throws \Exception
     */
    public function store(Request $request, ?string $id)
    {
        $nome = $request->json('nome');
        $idade = $request->json('idade');
        $password = $request->json('password');

        $data = [
            'name' => $nome,
            'idade' => $idade,
            'password' => $password
        ];

        $validator = new RequestValidator();
        $validator->rules([
            'name' => 'required|string|max:255|min:22',
            'idade' => 'required|string',
            'password' => 'required|password:min_length=8',
        ]);

        $validationResult = $validator->validate($data);

        var_dump($validationResult);

        Response::json(['pessoa' => [
            'nome' => $nome,
            'idade' => $idade,
            'id' => $id
        ]]);
    }
}