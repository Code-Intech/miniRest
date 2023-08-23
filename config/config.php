<?php

require_once(__DIR__ . "/../vendor/autoload.php");

use Dotenv\Dotenv;
use MiniRest\Database\DatabaseConnection;
use MiniRest\Http\Auth\Auth;

$dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../');
$dotenv->load();
new DatabaseConnection();

Auth::setSecretKey(getenv('SECRET_KEY'));
Auth::setTokenExpiration(getenv('JWT_TIME'));