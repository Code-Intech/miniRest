<?php

namespace MiniRest\Http\Middlewares;

use Closure;
use MiniRest\Http\Request\Request;

interface MiddlewareInterface
{
    public function handle(Request $request, Closure $next);
}