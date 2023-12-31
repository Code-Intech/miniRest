<?php

namespace MiniRest\Http\Controllers\Users;
use MiniRest\Actions\User\UserCreateAction;
use MiniRest\Actions\User\UserFlgStatusAction;
use MiniRest\Actions\User\UserUpdateAction;
use MiniRest\DTO\AddressCreateDTO;
use MiniRest\DTO\User\UserCreateDTO;
use MiniRest\DTO\User\UserFlgStatusDTO;
use MiniRest\Helpers\StatusCode\StatusCode;
use MiniRest\Http\Auth\Auth;
use MiniRest\Http\Controllers\Controller;
use MiniRest\Http\Request\Request;
use MiniRest\Http\Response\Response;
use MiniRest\Models\User;
use MiniRest\Repositories\UserRepository;

class UserController extends Controller
{
    public function index()
    {
        Response::json(['user' => $this->paginate(User::query())]);
    }

    public function me(Request $request)
    {
        $userId = Auth::id($request);
        Response::json((new UserRepository())->me($userId));
    }

    /**
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $validation = $request->rules([
            'nomeCompleto' => 'required|string|max:255',
            'dataNascimento' => 'required|string',
            'genero' => 'required',
            'telefone' => 'required|string',
            'email' => 'required|unique|string',
            'senha' => 'required|password:min_length=8',
            'cpf' => 'required|string|CPF',
            'cep' => 'required|string',
            'rua' => 'required|string',
            'bairro' => 'required|string',
            'cidade' => 'required|string',
            'estado' => 'required|string',
            'numero' => 'required'
        ])->validate();

        if (!$validation) {
            $request->errors();
            return;
        }

        $addressDTO = new AddressCreateDTO($request);
        $userDTO = new UserCreateDTO($request);

        (new UserCreateAction())->execute($userDTO, $addressDTO);

        Response::json([
            'message'=>'Usuário criado com sucesso!',
        ], StatusCode::CREATED);

    }

    public function update(Request $request)
    {
        $userId = Auth::id($request);
        $userAddressId = Auth::user($request)->address_id;
        $validation = $request->rules([
            'nomeCompleto' => 'required|string|max:255',
            'dataNascimento' => 'required|string',
            'genero' => 'required',
            'telefone' => 'required|string',
            'email' => 'required|string',
            'senha' => 'required|password:min_length=8',
            'cpf' => 'required|string',
            'cep' => 'required|string',
            'rua' => 'required|string',
            'bairro' => 'required|string',
            'cidade' => 'required|string',
            'estado' => 'required|string'
        ])->validate();

        if (!$validation) {
            $request->errors();
            return;
        }

        $addressDTO = new AddressCreateDTO($request);
        $userDTO = new UserCreateDTO($request);

        (new UserUpdateAction())->execute($userId, $userAddressId, $userDTO, $addressDTO);

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
        ]);

    }
}