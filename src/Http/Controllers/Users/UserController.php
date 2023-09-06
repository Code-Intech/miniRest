<?php

namespace MiniRest\Http\Controllers\Users;
use MiniRest\Actions\User\UserCreateAction;
use MiniRest\Helpers\StatusCode\StatusCode;
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
        $userDTO = new UserCreateDTO($request);
        $actionResult = (new UserCreateAction())->execute($userDTO);

        if (is_array($actionResult) && count($actionResult) > 0) {
            $erro = [];
            foreach ($actionResult as $item){
                $erro[] = $item[0];
            }
            Response::json(['errors' => $erro], StatusCode::REQUEST_ERROR);
            return;
        }

        Response::json([
            'message'=>'Usuário criado com sucesso',
        ], StatusCode::CREATED);

//        (new AuthController())->login($request);
    }
}