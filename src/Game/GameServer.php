<?php
namespace App\Game;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class GameServer implements MessageComponentInterface
{
    protected $clients;
    protected $scores;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->scores = [];
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        $this->scores[$conn->resourceId] = 0;
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);
        if (isset($data['action']) && $data['action'] === 'click') {
            $this->scores[$from->resourceId]++;
            $response = [
                'player_id' => $from->resourceId,
                'score' => $this->scores[$from->resourceId]
            ];
            foreach ($this->clients as $client) {
                $client->send(json_encode($response));
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        unset($this->scores[$conn->resourceId]);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}
