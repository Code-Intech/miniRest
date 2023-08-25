<?php

namespace MiniRest\Http\Middlewares;

use Closure;
use MiniRest\Exceptions\InvalidJWTToken;
use MiniRest\Http\Auth\Auth;
use MiniRest\Http\Request\Request;
use MiniRest\Http\Response\Response;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, $next)
    {
        try {
            if (Auth::check($request)) {
                return $next($request);
            } else {
                Response::json(['error' => 'Acesso não autorizado'], 403);
            }
        } catch (InvalidJWTToken $e) {
            Response::json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}
