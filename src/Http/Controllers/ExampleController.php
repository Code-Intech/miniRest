<?php

namespace MiniRest\Http\Controllers;
use MiniRest\Http\Request\Request;
use MiniRest\Http\Response\Response;
use MiniRest\Models\User;

class ExampleController extends Controller
{
    public function index()
    {
        Response::json(['user' => $this->paginate(User::query())]);
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

        $validator = $request->rules([
            'nome' => 'required|string|max:255|min:22',
            'idade' => 'required|string',
            'password' => 'required|password:min_length=8',
        ])->validate();

        if ($validator) {
            $erro = [];
             foreach ($validator as $item){
                 $erro[] = $item[0];
             }
            Response::json(['errors' => $erro], 400);
            return;
        }

        Response::json(['pessoa' => [
            'nome' => $nome,
            'idade' => $idade,
            'id' => $id
        ]]);
    }
}