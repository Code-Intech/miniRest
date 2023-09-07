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
     * @throws InvalidJWTToken|\Exception
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

        if (!$validator) {
            $request->errors();
            return;
        }

        try {
            $token = Auth::attempt($credentials);

            if ($token) {
                Response::json(['token' => $token]);
            }

        } catch (UserNotFoundException $e) {
            Response::json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function profile(Request $request): void
    {
        $userInfo = Auth::user($request);

        Response::json([
            'success' => 'Valid token',
            'user_id' => Auth::id($request),
            'user_email' => $userInfo->user_email,
            'user_name' => $userInfo->user_name
        ]);
        return;
    }
}