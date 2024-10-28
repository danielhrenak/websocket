<?php
namespace App\Game;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use PDO;

class GameFingersServer implements MessageComponentInterface
{
    protected $clients;
    private $roundInProgress = false;

    public function __construct() {
        $this->clients = new \SplObjectStorage();
    }

    public function onOpen(\Ratchet\ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection: ({$conn->resourceId})\n";
        $conn->send(json_encode(['type' => 'connected', 'message' => 'Welcome to Fastest Finger!']));
    }

    public function onMessage(\Ratchet\ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);

        if ($data['type'] === 'join') {
            // Notify all clients that a new player joined
            $this->broadcast(['type' => 'playerJoined', 'playerId' => $data['playerId']]);
        }

        if ($data['type'] === 'click' && $this->roundInProgress) {
            $this->roundInProgress = false;
            $this->broadcast(['type' => 'roundResult', 'winner' => $data['playerId']]);
        }
    }

    public function onClose(\Ratchet\ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(\Ratchet\ConnectionInterface $conn, \Exception $e) {
        echo "An error occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    // Start a round by notifying all clients to get ready
    public function startRound() {
        $this->roundInProgress = true;
        $this->broadcast(['type' => 'startRound']);

        // Wait 1-4 seconds, then broadcast "PRESS NOW!"
        $delay = rand(1000, 4000);
        usleep($delay * 1000);

        if ($this->roundInProgress) {
            $this->broadcast(['type' => 'pressNow']);
        }
    }

    private function broadcast($msg) {
        $message = json_encode($msg);
        foreach ($this->clients as $client) {
            $client->send($message);
        }
    }
}
