<?php

use MiniRest\Http\Controllers\AuthController;
use MiniRest\Http\Controllers\HealthController;
use MiniRest\Http\Controllers\Users\UserController;
use MiniRest\Http\Middlewares\AuthMiddleware;
use MiniRest\Router\Router;

Router::get('/health', [HealthController::class, 'health']);

Router::post('/auth/login', [AuthController::class, 'login']);
Router::post('/api/user/create', [UserController::class, 'store']);

Router::prefix('/api')->group([AuthMiddleware::class], function () {
    Router::get('/user/getAll', [UserController::class, 'index']);
    Router::get('/profile', [AuthController::class, 'profile']);

    Router::patch('/user/update', [UserController::class, 'update']);
    Router::patch('/user/update/flg', [UserController::class, 'removeUser']);
});