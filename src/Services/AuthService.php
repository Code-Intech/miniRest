<?php

namespace MiniRest\Services;

use Firebase\JWT\JWT;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use MiniRest\Exceptions\UserNotFoundException;
use MiniRest\Http\Auth\Auth;
use MiniRest\Models\User;

class AuthService
{
    /**
     * @throws UserNotFoundException
     */
    public function createToken(array $credentials): ?string
    {
        try {
            $user = User::where('Email', '=', $credentials['email'])
                ->firstOrFail();

            if (!password_verify($credentials['senha'], $user->Senha)) {
                return throw new UserNotFoundException();
            }

            if ($user) {
                $now = time();
                $expiration = $now + Auth::$tokenExpiration;

                $payload = [
                    'user_id' => $user->idtb_user,
                    'iat' => $now,       // Timestamp de emissão do token
                    'exp' => $expiration // Timestamp de expiração do token
                ];


            }
        } catch (ModelNotFoundException) {
            throw new UserNotFoundException();
        }

        return JWT::encode($payload, Auth::$secretKey, 'HS256');

    }
}