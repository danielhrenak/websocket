<?php

namespace App\Chat;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
class ChatServer implements MessageComponentInterface
{
    protected $clients;
    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }
    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);
        // You can handle the received message here, such as saving it to the database
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send($msg);
            }
        }
    }
    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}
