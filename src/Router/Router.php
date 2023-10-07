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

    protected array $routers = [];
    protected array $groupMiddlewares = [];
    protected mixed $prefix = '';

    public function __construct()
    {
    }

    public function set(string $method, $uri, $action, $middleware = []) {
        $this->add($method, $uri, $action, $middleware);
    }

    private function add($method, $route, $action, $middlewares = []): void
    {
        $pattern = '#^' . preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $this->prefix . $route) . '$#';

        $mergedMiddlewares = array_merge($this->groupMiddlewares, $middlewares);

        $this->routers[] = [
            'method' => $method,
            'route' => $pattern,
            'action' => $action,
            'middlewares' => $mergedMiddlewares,
        ];
    }

    /**
     * @throws \ReflectionException
     */
    public function dispatch(Request $request): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method == 'OPTIONS') {
            Response::json('');
        }

        $matches = [];

        foreach ($this->routers as $route)
        {
            if ($route['method'] === $method && preg_match($route['route'], $uri, $matches)) {
                array_shift($matches);
                $middlewareList = [];
                if (isset($route['middlewares']) && count($route['middlewares']) > 0) {

                    foreach ($route['middlewares'] as $middleware) {
                        $middlewareList[] = new $middleware();
                    }
                }

                $this->executeAction($request ,$route['action'], $matches, $middlewareList);
                return;
            }
        }

        Response::notFound();
    }

    /**
     * @throws \ReflectionException
     */
    protected function executeAction(Request $request, $action, $params, $middlewares = null): void
    {
        [$controllerClass, $method] = $action;

        $controller = new $controllerClass();

        [$parameters, $params] = $this->reflectionController($controllerClass, $method, $request, $params);

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
    protected function reflectionController(mixed $controllerClass, mixed $method, Request $request, $params): array
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

    public function prefix($prefix): Router
    {
        $this->prefix = $prefix;
        return $this;
    }

    public function group($middlewares, $callback): void
    {

        $groupMiddlewares = is_array($middlewares) ? $middlewares : [$middlewares];
        $this->groupMiddlewares = array_merge($this->groupMiddlewares, $groupMiddlewares);
        $callback();
        $this->groupMiddlewares = array_diff($this->groupMiddlewares, $groupMiddlewares);
        $this->prefix = '';
    }

}
