<?php

use MiniRest\Http\Middlewares\AuthMiddleware;
use MiniRest\Router\Router;

use MiniRest\Http\Controllers\{
    Avatar\AvatarController,
    AuthController,
    Categories\CategoriesController,
    Professions\ProfessionsController,
    Skills\SkillsController,
    Gender\GenderController,
    HealthController,
    Users\UserController,
};

$router = new Router();

$router->set('POST', '/auth/login', [AuthController::class, 'login']);
$router->set('POST', '/api/user/create', [UserController::class, 'store']);

$router->prefix('/api')->group([AuthMiddleware::class], function () use($router) {

    // User
    $router->set('GET', '/user/getAll', [UserController::class, 'index']);
    $router->set('PATCH', '/user/update', [UserController::class, 'update']);
    $router->set('DELETE', '/user/update/flg', [UserController::class, 'removeUser']);
    $router->set('GET', '/user/me', [UserController::class, 'me']);

    // User avatar upload
    $router->set('POST', '/user/upload/avatar', [AvatarController::class, 'uploadAvatar']);
    $router->set('GET', '/user/avatar', [AvatarController::class, 'avatar']);

    // Verify jwt token from logged user
    $router->set('GET', '/profile', [AuthController::class, 'profile']);

});

$router->set('GET', '/health', [HealthController::class, 'health']);
$router->set('GET', '/gender', [GenderController::class, 'index']);

//Categories
$router->set('GET', '/categories', [CategoriesController::class, 'index']);
$router->set('GET', '/professions', [ProfessionsController::class, 'index']);

//Skills
$router->set('GET', '/skills', [SkillsController::class, 'index']);

$router->dispatch(new \MiniRest\Http\Request\Request());