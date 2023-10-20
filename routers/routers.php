<?php

use MiniRest\Http\Middlewares\AuthMiddleware;
use MiniRest\Router\Router;

use MiniRest\Http\Controllers\{Avatar\AvatarController,
    AuthController,
    Categories\CategoriesController,
    Prestador\PrestadorController,
    Professions\ProfessionsController,
    Skills\SkillsController,
    Gender\GenderController,
    HealthController,
    Users\UserController};
use MiniRest\Http\Controllers\Services\ServiceController;

Router::post('/auth/login', [AuthController::class, 'login']);
Router::post('/api/user/create', [UserController::class, 'store']);

Router::prefix('/api')->group([AuthMiddleware::class], function () {

    // User
    Router::get('/user/getAll', [UserController::class, 'index']);
    Router::patch('/user/update', [UserController::class, 'update']);
    Router::delete('/user/update/flg', [UserController::class, 'removeUser']);
    Router::get('/user/me', [UserController::class, 'me']);

    // User avatar upload
    Router::post('/user/upload/avatar', [AvatarController::class, 'uploadAvatar']);
    Router::get('/user/avatar', [AvatarController::class, 'avatar']);

    // Verify jwt token from logged user
    Router::get('/profile', [AuthController::class, 'profile']);

    // Prestador
    Router::post('/prestador/create', [PrestadorController::class, 'store']);

    Router::patch('/prestador/update', [PrestadorController::class, 'update']);

    // Serviço
    Router::post('/servico/create', [ServiceController::class, 'store']);

});

Router::get('/health', [HealthController::class, 'health']);
Router::get('/gender', [GenderController::class, 'index']);

//Categories
Router::get('/categories', [CategoriesController::class, 'index']);
Router::get('/professions', [ProfessionsController::class, 'index']);

//Skills
Router::get('/skills', [SkillsController::class, 'index']);

//Prestadores
Router::get('/prestador', [PrestadorController::class, 'index']);
Router::get('/prestador/{id}', [PrestadorController::class, 'me']);