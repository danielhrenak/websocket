<?php
namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;

class GameFingersCommand extends Command
{
    public function execute(Arguments $args, ConsoleIo $io): int
    {
        $io->out('Starting WebSocket game server...');
        exec('php websocket-game-server-fingers.php');

        return static::CODE_SUCCESS;
    }
}
