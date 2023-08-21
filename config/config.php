<?php

require_once(__DIR__ . "/../vendor/autoload.php");

use Dotenv\Dotenv;
use MiniRest\Database\DatabaseConnection;

$dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../');
$dotenv->load();
new DatabaseConnection();