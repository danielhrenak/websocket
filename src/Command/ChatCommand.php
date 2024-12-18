<?php
// src/Shell/ChatShell.php
namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;

class ChatCommand extends Command
{
    public function execute(Arguments $args, ConsoleIo $io): int
    {
        $io->out('Starting WebSocket server...');
        exec('php websocket-chat-server.php');

        return static::CODE_SUCCESS;
    }
}
