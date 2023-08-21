<?php

require_once 'vendor/autoload.php';
require_once 'config/config.php';
use MiniRest\Core\App;

$app = new App();
$app->run();