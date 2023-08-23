<?php

namespace MiniRest\Http\Middlewares;

use MiniRest\Http\Auth\Auth;
use MiniRest\Http\Request\Request;
use MiniRest\Http\Response\Response;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, $params, $next)
    {
        if (Auth::check($request)) {
            return $next($request, $params);
        } else {
            Response::json(['error' => 'Acesso não autorizado'], 403);
        }
    }
}
