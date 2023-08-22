<?php

namespace MiniRest\Core;
use MiniRest\Http\Request\Request;
use MiniRest\Router\Router;

class App {
    /**
     * @throws \ReflectionException
     */
    public function run()
    {
        require_once(__DIR__ . "/../../routers/routers.php");
        Router::dispatch(new Request());
    }
}