<?php

namespace MiniRest\Router;
use MiniRest\Helpers\StatusCode\StatusCode;
use MiniRest\Http\Middlewares\MiddlewarePipeline;
use MiniRest\Http\Request\Request;
use MiniRest\Http\Response\Response;

class Router {

    private const METHOD_GET = 'GET';
    private const METHOD_POST = 'POST';
    private const METHOD_PUT = 'PUT';
    private const METHOD_PATCH = 'PATCH';
    private const METHOD_DELETE = 'DELETE';

    protected static array $routers = [];
    private static array $groupMiddlewares = [];
    private static mixed $prefix = '';

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
        $pattern = '#^' . preg_replace('/\{([a-zA-Z0-9_]+)\??\}/', '(?P<$1>[^/]+)?', self::$prefix . $route) . '$#';

        $mergedMiddlewares = array_merge(self::$groupMiddlewares, $middlewares);

        self::$routers[] = [
            'method' => $method,
            'route' => $pattern, // Padrão regex com parâmetros capturados
            'action' => $action,
            'middlewares' => $mergedMiddlewares,
        ];
    }

    /**
     * @throws \ReflectionException
     */
    public static function dispatch(Request $request): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];


        if ($_SERVER['HTTP_HOST'] !== 'localhost:8000') {
            if ($_SERVER['HTTPS'] !== 'on') {
                $url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                header('Location: ' . $url, true, 301);
                exit();
            }
        }

        if ($method == 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        $matches = [];

        foreach (self::$routers as $route)
        {
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

        Response::notFound();
    }

    /**
     * @throws \ReflectionException
     */
    protected static function executeAction(Request $request, $action, $params, $middlewares = null): void
    {
        [$controllerClass, $method] = $action;

        $controller = new $controllerClass();

        [$parameters, $params] = self::reflectionController($controllerClass, $method, $request, $params);

        if (!$middlewares) {

            $controller->$method(...$parameters);
            return;
        }

        $middlewarePipeline = new MiddlewarePipeline();
        $middlewarePipeline->send($request)->through($middlewares);
        $middlewarePipeline->then(function ($passable) use ($controller, $method, $parameters) {
            $controller->$method(...$parameters);
        });
    }

    /**
     * @param mixed $controllerClass
     * @param mixed $method
     * @param Request $request
     * @param $params
     * @return array
     * @throws \ReflectionException
     */
    protected static function reflectionController(mixed $controllerClass, mixed $method, Request $request, $params): array
    {
        $reflectionMethod = new \ReflectionMethod($controllerClass, $method);
        $parameters = [];

        foreach ($reflectionMethod->getParameters() as $param) {
            if ($param->getType() && $param->getType()->getName() === Request::class) {
                $parameters[] = $request;
            } else {
                $parameters[] = array_shift($params);
            }
        }
        return array($parameters, $params);
    }

    public static function prefix($prefix): Router
    {
        self::$prefix = $prefix;
        return new Router();
    }

    public function group($middlewares, $callback): void
    {

        $groupMiddlewares = is_array($middlewares) ? $middlewares : [$middlewares];
        self::$groupMiddlewares = array_merge(self::$groupMiddlewares, $groupMiddlewares);
        $callback();
        self::$groupMiddlewares = array_diff(self::$groupMiddlewares, $groupMiddlewares);
        self::$prefix = '';
    }

}