<?php

use MiniRest\Http\Controllers\AuthController;
use MiniRest\Http\Controllers\ExampleController;
use MiniRest\Http\Middlewares\AuthMiddleware;
use MiniRest\Http\Middlewares\ExampleMiddleware;
use MiniRest\Router\Router;

//Router::get('/store/getAll', [ExampleController::class, 'index']);
Router::get('/store', [ExampleController::class, 'teste']);
Router::post('/store/{id}', [ExampleController::class, 'store'], []);
Router::put('/store', [ExampleController::class, 'store']);
Router::delete('/store', [ExampleController::class, 'store']);

Router::post('/auth/login', [AuthController::class, 'login']);

Router::get('/store/getAll', [ExampleController::class, 'index'], [AuthMiddleware::class]);