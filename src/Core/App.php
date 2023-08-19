<?php

namespace MiniRest\Core;
use MiniRest\Router\Router;

class App {
    public function run()
    {
        require_once(__DIR__ . "/../../routers/routers.php");
        Router::dispatch();
    }
}