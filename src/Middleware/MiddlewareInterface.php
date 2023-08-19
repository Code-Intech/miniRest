<?php

namespace MiniRest\Middleware;

use MiniRest\Http\Request;

interface MiddlewareInterface
{
    public function handle(Request $request, $params, callable $next);
}