<?php

//web socket server

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use App\Chat;
use Ratchet\WebSocket\WsServer;

require __DIR__ . '/vendor/autoload.php';

$server = IoServer::factory(
    new HttpServer(new WsServer(new Chat())),
    8081
);

$server->run();
