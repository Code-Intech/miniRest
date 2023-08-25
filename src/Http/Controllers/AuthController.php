<?php

namespace MiniRest\Http\Controllers;

use MiniRest\Http\Auth\Auth;
use MiniRest\Http\Request\Request;
use MiniRest\Http\Response\Response;

class AuthController
{
    public function login(Request $request): void
    {
        $credentials = [
            'email' => $request->json('email'),
            'password' => $request->json('password'),
        ];

        $validator = $request->rules([
            'email' => 'required|email',
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

        $token = Auth::attempt($credentials);
        if ($token) {
            Response::json(['token' => $token]);
        } else {
            Response::json(['error' => 'Credenciais inválidas'], 401);
        }
    }
}