<?php

namespace MiniRest\Http\Middlewares;

use Closure;
use MiniRest\Http\Request\Request;

class ExampleMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->json('email') !== "ms5806166@gmail.com") {
            var_dump('teste');
            return 1;
        }
        return $next($request);
    }
}