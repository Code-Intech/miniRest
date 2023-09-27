<?php

use MiniRest\Http\Controllers\AuthController;
use MiniRest\Http\Controllers\Gender\GenderController;
use MiniRest\Http\Controllers\HealthController;
use MiniRest\Http\Controllers\Upload\UploadController;
use MiniRest\Http\Controllers\Users\UserController;
use MiniRest\Http\Middlewares\AuthMiddleware;
use MiniRest\Router\Router;

Router::post('/auth/login', [AuthController::class, 'login']);
Router::post('/api/user/create', [UserController::class, 'store']);

Router::prefix('/api')->group([AuthMiddleware::class], function () {

    // User
    Router::get('/user/getAll', [UserController::class, 'index']);
    Router::patch('/user/update', [UserController::class, 'update']);
    Router::delete('/user/update/flg', [UserController::class, 'removeUser']);
    Router::get('/profile', [AuthController::class, 'profile']);
    Router::get('/user/me', [UserController::class, 'me']);

    // file upload
    Router::post('/upload', [UploadController::class, 'upload']);
});

Router::get('/health', [HealthController::class, 'health']);
Router::get('/gender', [GenderController::class, 'index']);