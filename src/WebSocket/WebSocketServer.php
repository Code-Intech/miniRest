<?php

use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Server\IoServer;
use MiniRest\WebSocket\Chat; // Substitua pelo seu manipulador WebSocket

require 'vendor/autoload.php';

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    8080 // Porta WebSocket
);

$server->run();