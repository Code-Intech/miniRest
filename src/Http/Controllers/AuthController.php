<?php

namespace MiniRest\Http\Controllers;

use MiniRest\Exceptions\InvalidJWTToken;
use MiniRest\Exceptions\UserNotFoundException;
use MiniRest\Http\Auth\Auth;
use MiniRest\Http\Request\Request;
use MiniRest\Http\Response\Response;

class AuthController
{
    /**
     * @throws InvalidJWTToken
     */
    public function login(Request $request): void
    {
        $credentials = [
            'email' => $request->json('email'),
            'senha' => $request->json('senha'),
        ];

        $validator = $request->rules([
            'email' => 'required|email',
            'senha' => 'required|password:min_length=8',
        ])->validate();

        if (is_array($validator) && count($validator) > 0) {
            $erro = [];
            foreach ($validator as $item){
                $erro[] = $item[0];
            }
            Response::json(['errors' => $erro], 400);
            return;
        }
        try {
            $token = Auth::attempt($credentials);

            if ($token) {
                Response::json(['token' => $token], 201);
            }

        } catch (UserNotFoundException $e) {
            Response::json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function profile(Request $request)
    {
        Response::json([
            'success' => 'Valid token',
            'user_id' => Auth::id($request)
        ]);
        return;
    }
}