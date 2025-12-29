<\?php

namespace App\Commands;

use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

#[AsCommand(
    name: '<?= $commandName ?>',
    description: '<?= $commandDescription ?>',
    help: 'This command allows <?= $commandDescription ?>',
    usages: ['']
)]
class <?= $commandClassName ?>Command
{
    public function __invoke(): int
    {

        return Command::SUCCESS;
    }
}