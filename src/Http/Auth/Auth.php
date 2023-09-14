<?php

namespace MiniRest\Http\Auth;

use DomainException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use MiniRest\Exceptions\AccessNotAllowedException;
use MiniRest\Exceptions\InvalidJWTToken;
use MiniRest\Exceptions\UserNotFoundException;
use MiniRest\Http\Request\Request;
use MiniRest\Services\AuthService;

class Auth
{

    private AuthService $authService;
    public static string $secretKey;
    public static int $tokenExpiration; // Tempo de expiração em segundos (1 hora)

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    /**
     * @throws UserNotFoundException
     */
    public static function attempt(array $credentials): bool|string
    {
        try {
            return (new AuthService())->createToken($credentials);
        } catch (UserNotFoundException|AccessNotAllowedException $e) {
            throw new UserNotFoundException($e->getMessage());
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
        } catch (ExpiredException|DomainException) {
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