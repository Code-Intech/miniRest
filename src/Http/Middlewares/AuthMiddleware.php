<?php

namespace MiniRest\Http\Middlewares;

use MiniRest\Exceptions\InvalidJWTToken;
use MiniRest\Exceptions\Validations\TokenValidationException;
use MiniRest\Http\Auth\Auth;
use MiniRest\Http\Request\Request;
use MiniRest\Http\Response\Response;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, $params, $next)
    {
        try {
            if (Auth::check($request)) {
                return $next($request, $params);
            } else {
                Response::json(['error' => 'Acesso não autorizado'], 403);
            }
        } catch (TokenValidationException $e) {
            Response::json(['error' => $e->getMessage()], 403);
        }
    }
}
