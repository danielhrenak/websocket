Example from https://clouddevs.com/cakephp/real-time-chat-application/


### Adjustmenst for Sqlite in step 3:
```php
'Datasources' => [
    'default' => [
        'className' => 'Cake\Database\Connection',
        'driver' => 'Cake\Database\Driver\Sqlite',
        'database' => 'path/to/your/sqlite/database.db',
        // other configuration options as needed
    ],
],
```

## In step 5 create ChatCommand class
- Shell is deprecated from Cakephp 4.0

```php
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
        exec('php websocket-server.php');

        return static::CODE_SUCCESS;
    }
}

```
