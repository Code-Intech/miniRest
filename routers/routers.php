<?php

use MiniRest\Actions\Servico\ServicoUploadImageAction;
use MiniRest\Http\Middlewares\AuthMiddleware;
use MiniRest\Router\Router;

use MiniRest\Http\Controllers\{Avatar\AvatarController,
    AuthController,
    Categories\CategoriesController,
    Portifolio\PortilioController,
    Prestador\PrestadorController,
    Professions\ProfessionsController,
    Skills\SkillsController,
    Gender\GenderController,
    HealthController,
    Users\UserController};
use MiniRest\Http\Controllers\Contratante\ContratanteController;
use MiniRest\Http\Controllers\Servico\ServicoController;
use MiniRest\Http\Controllers\Servico\ServicoImgController;
use MiniRest\Models\Servico\Servico;
use MiniRest\Http\Controllers\Proposta\PropostaController;

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
    Router::post('/servico/upload/img/{id}', [ServicoController::class, 'uploadImage']);
    Router::patch('/servico/update/{id}', [ServicoController::class, 'update']);
    Router::patch('/servico/update/profissoes/{id}', [ServicoController::class,'updateProfissao']);
    Router::patch('/servico/update/habilidades/{id}', [ServicoController::class, 'updateHabilidade']);
    Router::patch('/servico/delete/{id}', [ServicoController::class, 'deleteServico']);
    Router::get('/servico/me', [ServicoController::class, 'me']);

    //Proposta
    Router::post('/servico/proposta/{id}', [PropostaController::class, 'create']);
    Router::patch('/servico/proposta/aceitar/{id}', [PropostaController::class, 'accept']);
    Router::delete('/servico/proposta/delete/{id}', [PropostaController::class, 'delete']);
    Router::get('/servico/proposta/get/{id}', [PropostaController::class, 'getAll']);


    // Portifólio
    Router::post('/portifolio/create', [PortilioController::class, 'store']);
    Router::post('/portifolio/update/{id}', [PortilioController::class, 'update']);
    Router::post('/portifolio/add/{id}', [PortilioController::class, 'putPhoto']);

    Router::delete('/album/remove/{id}', [PortilioController::class, 'deleteAlbumPhotoById']);
    Router::delete('/portifolio/album/{id}', [PortilioController::class, 'deleteAlbumById']);

    Router::get('/portifolio', [PortilioController::class, 'getUserPortifolios']);
    Router::get('/album/{id}', [PortilioController::class, 'getPortifolioAlbumById']);


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

// Portifólio
Router::get('/portifolio/{id}', [PortilioController::class, 'getPortifoliosByUserId']);

