<?php

require __DIR__ . '/vendor/autoload.php';

$client = new WebSocket\Client("ws://127.0.0.1:8081");
$client->text(json_encode([
    'email' => 'server',
    'message' => 'hello everyone!'
]));
//echo $client->receive();
$client->close();