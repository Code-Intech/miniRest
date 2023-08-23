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

        $token = Auth::attempt($credentials);

        if ($token) {
            Response::json(['token' => $token, 'user' => $credentials]);
        } else {
            Response::json(['error' => 'Credenciais inválidas'], 401);
        }
    }
}