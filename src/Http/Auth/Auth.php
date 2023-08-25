<?php

namespace MiniRest\Http\Auth;

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use MiniRest\Exceptions\InvalidJWTToken;
use MiniRest\Http\Request\Request;
use MiniRest\Models\User;

class Auth
{
    private static string $secretKey;
    private static int $tokenExpiration; // Tempo de expiração em segundos (1 hora)

    public static function attempt(array $credentials)
    {
        try {
            $user = User::where('Email', '=', $credentials['email'])
                ->firstOrFail();

            if (!password_verify($credentials['password'], $user->Senha)) {
                return null;
            }

            if ($user) {
                $now = time();
                $expiration = $now + self::$tokenExpiration;

                $payload = [
                    'user_id' => $user->idtb_user,
                    'iat' => $now,       // Timestamp de emissão do token
                    'exp' => $expiration // Timestamp de expiração do token
                ];

                return JWT::encode($payload, self::$secretKey, 'HS256');
            }
        } catch (ModelNotFoundException) {
            return null;
        }
    }

    /**
     * @throws InvalidJWTToken
     */
    public static function check(Request $request): bool
    {
        $token = self::getTokenFromRequest($request);
        return self::validateToken($token);
    }

    /**
     * @throws InvalidJWTToken
     */
    public static function user(Request $request): ?object
    {
        $token = self::getTokenFromRequest($request);

        if (self::validateToken($token)) {
            $decodedToken = JWT::decode($token, new Key(self::$secretKey, 'HS256'));
            return (object) $decodedToken;
        }

        return null;
    }

    public static function id(Request $request): ?int
    {
        $user = self::user($request);
        return $user ? $user->user_id : null;
    }

    private static function getTokenFromRequest(Request $request): ?string
    {
        $authorizationHeader = $request->headers('Authorization');

        if (preg_match('/Bearer\s+(.*)/', $authorizationHeader, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * @throws InvalidJWTToken
     */
    public static function validateToken($token): bool
    {
        if ($token === null) throw new InvalidJWTToken('Token NULL, Token inválido.');

        try {
            JWT::decode($token, new Key(self::$secretKey, 'HS256'));
            return true;
        } catch (ExpiredException) {
            throw new InvalidJWTToken();
        }

    }

    /**
     * @param string $secretKey
     */
    public static function setSecretKey(string $secretKey): void
    {
        self::$secretKey = $secretKey;
    }

    /**
     * @param int $tokenExpiration
     */
    public static function setTokenExpiration(int $tokenExpiration): void
    {
        self::$tokenExpiration = $tokenExpiration;
    }
}