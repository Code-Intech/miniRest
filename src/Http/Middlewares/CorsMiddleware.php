<?php

namespace MiniRest\Http\Middlewares;

use MiniRest\Http\Request\Request;
use MiniRest\Http\Response\Response;

class CorsMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, $params, $next)
    {
        $response = $next($request, $params);

        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Max-Age: 1000");
        header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
        header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
        return $response;
    }
}