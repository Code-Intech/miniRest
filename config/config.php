<?php

require_once(__DIR__ . "/../vendor/autoload.php");

use Dotenv\Dotenv;
use MiniRest\Database\DatabaseConnection;
use MiniRest\Http\Auth\Auth;

date_default_timezone_set('America/Sao_Paulo');

$dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../');
$dotenv->load();
new DatabaseConnection();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, PATCH, POST, GET, OPTIONS, DELETE");
header('Strict-Transport-Security: max-age=31536000');

Auth::setSecretKey(getenv('SECRET_KEY'));
Auth::setTokenExpiration(getenv('JWT_TIME'));