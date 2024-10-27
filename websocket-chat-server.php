<?php
// websocket-server.php
require __DIR__ . '/vendor/autoload.php';

use App\Chat\ChatServer;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;


$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new ChatServer()
        )
    ),
    8080
);
echo "WebSocket game server started\n";
$server->run();

