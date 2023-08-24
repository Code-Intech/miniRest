<?php

namespace MiniRest\Router;
use MiniRest\Http\Middlewares\CorsMiddleware;
use MiniRest\Http\Request\Request;
use MiniRest\Http\Response\Response;

class Router {

    private const METHOD_GET = 'GET';
    private const METHOD_POST = 'POST';
    private const METHOD_PUT = 'PUT';
    private const METHOD_PATCH = 'PATCH';
    private const METHOD_DELETE = 'DELETE';

    protected static array $routers = [];

    public function __construct()
    {
    }

    public static function get($uri, $action, $middleware = []){
        self::add(Router::METHOD_GET, $uri, $action, $middleware);
    }

    public static function post($uri, $action, $middleware = []) {
        self::add(Router::METHOD_POST, $uri, $action, $middleware);
    }

    public static function put($uri, $action, $middleware = []) {
        self::add(Router::METHOD_PUT, $uri, $action, $middleware);
    }

    public static function delete($uri, $action, $middleware = []) {
        self::add(Router::METHOD_DELETE, $uri, $action, $middleware);
    }

    public static function patch($uri, $action, $middleware = []) {
        self::add(Router::METHOD_PATCH, $uri, $action, $middleware);
    }

    private static function add($method, $route, $action, $middlewares = []): void
    {
        $pattern = '#^' . preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $route) . '$#';
        self::$routers[] = [
            'method' => $method,
            'route' => $pattern, // Padrão regex com parâmetros capturados
            'action' => $action,
            'middlewares' => $middlewares,
        ];
    }

    /**
     * @throws \ReflectionException
     */
    public static function dispatch(Request $request): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        $matches = [];

        foreach (self::$routers as $route)
        {
            $route = self::gloabalMiddleware($route);

            if ($route['method'] === $method && preg_match($route['route'], $uri, $matches)) {
                array_shift($matches);
                $middlewareList = [];
                if (isset($route['middlewares']) && count($route['middlewares']) > 0) {

                    foreach ($route['middlewares'] as $middleware) {
                        $middlewareList[] = new $middleware();
                    }
                }

                self::executeAction($request ,$route['action'], $matches, $middlewareList);
                return;
            }
        }

        Response::json(['error' => 'Route not found'], 404);
    }

    /**
     * @throws \ReflectionException
     */
    protected static function executeAction(Request $request, $action, $params, $middlewares = null): void
    {
        [$controllerClass, $method] = $action;

        $controller = new $controllerClass();

        $reflectionMethod = new \ReflectionMethod($controllerClass, $method);
        $parameters = [];

        foreach ($reflectionMethod->getParameters() as $param) {
            if ($param->getType() && $param->getType()->getName() === Request::class) {
                $parameters[] = $request;
            } else {
                $parameters[] = array_shift($params);
            }
        }

        if ($middlewares) {
            foreach ($middlewares as $middleware) {
                $middleware->handle($request, $params, function () use ($controller, $method, $parameters) {
                    $controller->$method(...$parameters);
                });
            }
        } else {
            $controller->$method(...$parameters);
        }
    }

    /**
     * @param mixed $route
     * @return array|mixed
     */
    public static function gloabalMiddleware(mixed $route): mixed
    {
        $route['middlewares'][] = CorsMiddleware::class;
        return $route;
    }

}