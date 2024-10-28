<?php
// websocket-server.php
require __DIR__ . '/vendor/autoload.php';

use App\Game\GameFingersServer;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\Socket\Server as ReactServer;

// Create event loop and start the WebSocket server
$loop = Factory::create();
$socket = new ReactServer('0.0.0.0:8080', $loop);
$gameServer = new GameFingersServer();

// Run WebSocket server on port 8080
$webSocketServer = new IoServer(
    new HttpServer(
        new WsServer($gameServer)
    ),
    $socket,
    $loop
);

echo "WebSocket game fingers server started\n";

// Start a new game round every 5 seconds
$loop->addPeriodicTimer(5, function() use ($gameServer) {
    $gameServer->startRound();
});

// Run the loop
$loop->run();
