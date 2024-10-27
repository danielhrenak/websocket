<?php
namespace App\Game;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use PDO;

class GameServer implements MessageComponentInterface
{
    protected $clients;
    protected $db;
    protected $usernames;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->db = new PDO('sqlite:' . __DIR__ . '/../../sqlite/database.db');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->initializeDatabase();
        $this->usernames = [];
    }

    protected function initializeDatabase()
    {
        $this->db->exec("CREATE TABLE IF NOT EXISTS sessions (
            id INTEGER PRIMARY KEY,
            resource_id INTEGER UNIQUE,
            username TEXT,
            score INTEGER
        )");
        $this->db->exec("CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY,
            username TEXT UNIQUE
        )");
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);
        if (isset($data['action']) && $data['action'] === 'login' && isset($data['username'])) {
            $username = $data['username'];
            $this->usernames[] = $username;

            $stmt = $this->db->prepare("INSERT OR IGNORE INTO users (username) VALUES (:username)");
            $stmt->execute([':username' => $username]);

            $stmt = $this->db->prepare("SELECT username FROM sessions WHERE username = :username");
            $stmt->execute([':username' => $username]);
            $existingUsername = $stmt->fetchColumn();
            if (!$existingUsername) {
                $stmt = $this->db->prepare("INSERT INTO sessions (username, score) VALUES (:username, 0)");
                $stmt->execute([':username' => $username]);
            }

            $response = [
                'action' => 'login',
                'status' => 'success',
                'username' => $username
            ];
            $from->send(json_encode($response));
        } elseif (isset($data['action']) && $data['action'] === 'click') {
            $username = $data['username'];
            $stmt = $this->db->prepare("UPDATE sessions SET score = score + 1 WHERE username = :username");
            $stmt->execute([':username' => $username]);

            $stmt = $this->db->prepare("SELECT score FROM sessions WHERE username = :username");
            $stmt->execute([':username' => $username]);
            $score = $stmt->fetchColumn();

            $response = [
                'action' => 'click',
                'player_id' => $from->resourceId,
                'username' => $username,
                'score' => $score
            ];
            foreach ($this->clients as $client) {
                $client->send(json_encode($response));
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        unset($this->usernames[$conn->resourceId]);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}
