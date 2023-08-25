<?php

namespace MiniRest\Http\Middlewares;

use Closure;
use MiniRest\Http\Request\Request;

class MiddlewarePipeline
{
    protected $middlewares = [];
    protected $passable;

    public function send(Request $request)
    {
        $this->passable = $request;
        return $this;
    }

    public function through(array $middlewares)
    {
        $this->middlewares = $middlewares;
        return $this;
    }

    public function then(Closure $destination)
    {
        $middlewares = array_reverse($this->middlewares);

        $next = $destination;

        foreach ($middlewares as $middleware) {
            $next = function ($passable) use ($middleware, $next) {
                return (new $middleware())->handle($passable, $next);
            };
        }

        return $next($this->passable);
    }
}