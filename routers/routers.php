<?php

use MiniRest\Controllers\ExampleController;
use MiniRest\Router\Router;

Router::get('/store/getAll', [ExampleController::class, 'index']);
Router::get('/store/', [ExampleController::class, 'teste']);
Router::post( '/store', [ExampleController::class, 'store']);
Router::put( '/store', [ExampleController::class, 'store']);
Router::delete( '/store', [ExampleController::class, 'store']);