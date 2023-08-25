<?php

namespace MiniRest\Http\Auth;

use DomainException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use MiniRest\Exceptions\InvalidJWTToken;
use MiniRest\Exceptions\Validations\TokenValidationException;
use MiniRest\Http\Request\Request;

class Auth
{
    private static string $secretKey;
    private static int $tokenExpiration; // Tempo de expiração em segundos (1 hora)

    public static function attempt(array $credentials): ?string
    {
        if ($credentials['email'] === 'ms5806166@gmail.com' && $credentials['password'] === 'Teste1236@') {

            $now = time();
            $expiration = $now + self::$tokenExpiration;

            $payload = [
                'user_id' => 1,
                'iat' => $now,       // Timestamp de emissão do token
                'exp' => $expiration // Timestamp de expiração do token
            ];

            return JWT::encode($payload, self::$secretKey, 'HS256');
        }

        return null;
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
     * @throws TokenValidationException
     */
    public static function validateToken($token): bool
    {
        if ($token === null) throw new TokenValidationException('Token NULL, Token inválido.');

        try {
            JWT::decode($token, new Key(self::$secretKey, 'HS256'));
            return true;
        } catch (DomainException) {
            throw new TokenValidationException();
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