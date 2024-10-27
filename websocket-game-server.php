<?php
// websocket-server.php
require __DIR__ . '/vendor/autoload.php';

use App\Game\GameServer;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;


$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new GameServer()
        )
    ),
    8080
);
echo "WebSocket chat server started\n";
$server->run();

