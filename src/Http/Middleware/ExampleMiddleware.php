<?php

namespace MiniRest\Http\Middleware;

use MiniRest\Http\Request;
use MiniRest\Http\Response;

class ExampleMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, $params, callable $next)
    {
        if ((int)$params[0] > 100) return Response::json(['error' => 'Access not allowed'], 401);

        return $next($request, $params);
    }
}