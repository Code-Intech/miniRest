<?php

namespace MiniRest\Http\Controllers\Users;
use MiniRest\Actions\User\UserCreateAction;
use MiniRest\Actions\User\UserFlgStatusAction;
use MiniRest\Actions\User\UserUpdateAction;
use MiniRest\Helpers\StatusCode\StatusCode;
use MiniRest\Http\Auth\Auth;
use MiniRest\Http\Controllers\AuthController;
use MiniRest\Http\Controllers\Controller;
use MiniRest\Http\Request\Request;
use MiniRest\Http\Response\Response;
use MiniRest\Models\User;

class UserController extends Controller
{
    public function index()
    {
        Response::json(['user' => $this->paginate(User::query())]);
    }

    /**
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $validation = $request->rules([
            'nomeCompleto' => 'required|string|max:255',
            'dataNascimento' => 'required|string',
            'genero' => 'required|string',
            'telefone' => 'required|string',
            'email' => 'required|string',
            'senha' => 'required|password:min_length=8',
            'cpf' => 'required|string',
            'cep' => 'required|string',
            'rua' => 'required|string',
            'regiao' => 'required|string',
            'bairro' => 'required|string'
        ])->validate();

        if (!$validation) {
            $request->errors();
            return;
        }

        $userDTO = new UserCreateDTO($request);

        (new UserCreateAction())->execute($userDTO);

        Response::json([
            'message'=>'Usuário criado com sucesso',
        ], StatusCode::CREATED);

    }

    public function update(Request $request)
    {
        $userId = Auth::id($request);

        $validation = $request->rules([
            'nomeCompleto' => 'required|string|max:255',
            'dataNascimento' => 'required|string',
            'genero' => 'required|string',
            'telefone' => 'required|string',
            'email' => 'required|string',
            'senha' => 'required|password:min_length=8',
            'cpf' => 'required|string',
            'cep' => 'required|string',
            'rua' => 'required|string',
            'regiao' => 'required|string',
            'bairro' => 'required|string'
        ])->validate();

        if (!$validation) {
            $request->errors();
            return;
        }

        $userDTO = new UserCreateDTO($request);

        (new UserUpdateAction())->execute($userId, $userDTO);

        Response::json([
            'message'=>'Usuário atualizado com sucesso',
        ]);

    }

    public function removeUser(Request $request)
    {
        $validation = $request->rules([
            'flg' => 'required'
        ])->validate();

        if (!$validation) {
            $request->errors();
            return;
        }

        $userId = Auth::id($request);

        $userDTO = new UserFlgStatusDTO($request);

        (new UserFlgStatusAction())->execute($userId, $userDTO);

        Response::json([
            'message'=>'Usuário "flegado" com sucesso',
        ], StatusCode::CREATED);

    }
}