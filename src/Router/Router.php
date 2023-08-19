<?php

namespace MiniRest\Router;
use MiniRest\Http\Request;
use MiniRest\Http\Response;

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

    public static function get($uri, $action) {
        self::add(Router::METHOD_GET, $uri, $action);
    }

    public static function post($uri, $action) {
        self::add(Router::METHOD_POST, $uri, $action);
    }

    public static function put($uri, $action) {
        self::add(Router::METHOD_PUT, $uri, $action);
    }

    public static function delete($uri, $action) {
        self::add(Router::METHOD_DELETE, $uri, $action);
    }

    public static function patch($uri, $action) {
        self::add(Router::METHOD_PATCH, $uri, $action);
    }

    private static function add($method, $route, $action): void
    {
        self::$routers[] = [
            'method' => $method,
            'route' => $route,
            'action' => $action,
        ];
    }

    public static function dispatch(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        foreach (self::$routers as $route)
        {
            if ($route['method'] === $method && self::matchRoute($route['route'], $uri)) {

                self::executeAction($route['action'], $uri);
                return;
            }
        }

        Response::json(['error' => 'Route not found'], 404);
    }

    protected static function matchRoute($pattern, $uri) {
        $pattern = preg_replace('/\{.*?\}/', '([^/]+)', $pattern);
        return preg_match("#^$pattern$#", $uri);
    }

    /**
     * @throws \ReflectionException
     */
    protected static function executeAction($action, $uri): void
    {
        [$controllerClass, $method] = $action;

        $controller = new $controllerClass();

        $reflectionMethod = new \ReflectionMethod($controllerClass, $method);
        $parameters = [];

        foreach ($reflectionMethod->getParameters() as $param) {
            if ($param->getType() && $param->getType()->getName() === Request::class) {
                $parameters[] = new Request();
            }
        }

        $controller->$method(...$parameters);
    }
}