<?php

use MiniRest\Http\Controllers\AuthController;
use MiniRest\Http\Controllers\Categories\CategoriesController;
use MiniRest\Http\Controllers\Professions\ProfessionsController;
use MiniRest\Http\Controllers\Skills\SkillsController;
use MiniRest\Http\Controllers\Gender\GenderController;
use MiniRest\Http\Controllers\HealthController;
use MiniRest\Http\Controllers\Upload\UploadControllerExample;
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
    Router::get('/user/me', [UserController::class, 'me']);

    // User avatar upload
    Router::post('/user/avatar', [UserController::class, 'avatar']);

    // Verify jwt token from logged user
    Router::get('/profile', [AuthController::class, 'profile']);

});

Router::get('/health', [HealthController::class, 'health']);
Router::get('/gender', [GenderController::class, 'index']);

//Categories
Router::get('/categories',[CategoriesController::class, 'index']);
Router::get('/professions',[ProfessionsController::class, 'index']);

//Skills
Router::get('/skills',[SkillsController::class, 'index']);
