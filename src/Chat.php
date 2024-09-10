<?php

namespace App;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface
{
    protected $clients;
    protected $clientData = [];

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, JSON_OBJECT_AS_ARRAY);

        echo sprintf('Connection %d sending message "%s" to %d other connections' . PHP_EOL
            , $from->resourceId, $msg, count($this->clients));

        if (!empty($data['email'])) {
            $email = $data['email'];
            $this->clientData[$from->resourceId] = [
                'email' => $email,
            ];
            echo 'Init user connection: ' . $email . PHP_EOL;

        }
        if (!empty($data['message'])) {
            foreach ($this->clients as $client) {
                if (true/*$from !== $client*/) {
                    $client->send(json_encode([
                        'email' => $this->clientData[$from->resourceId]['email'] ?? 'noname',
                        'message' => $data['message'],
                        'connections' => count($this->clients)
                    ]));
                }
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        unset($this->clientData[$conn->resourceId]);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}