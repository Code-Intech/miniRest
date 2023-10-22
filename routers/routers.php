<?php

use MiniRest\Actions\Servico\ServicoUploadImageAction;
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
use MiniRest\Http\Controllers\Contratante\ContratanteController;
use MiniRest\Http\Controllers\Servico\ServicoController;
use MiniRest\Http\Controllers\Servico\ServicoImgController;

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
    Router::get('/prestador/me', [PrestadorController::class, 'me']);

    //Contratate
    Router::post('/contratante/create', [ContratanteController::class, 'store']);

    // Serviço
    Router::post('/servico/create', [ServicoController::class, 'store']);
    Router::patch('/servico/update/{id}', [ServicoController::class, 'update']);
    Router::get('/servico/me', [ServicoController::class, 'me']);

    // Serviço Image upload
    Router::post('/servico/upload/image/{id}', [ServicoImgController::class, 'uploadImage']);


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
Router::get('/prestador/{id}', [PrestadorController::class, 'findById']);

//Serviços
Router::get('/servico', [ServicoController::class, 'index']);
Router::get('/servico/{id}', [ServicoController::class, 'findById']);
