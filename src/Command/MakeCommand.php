<?php

declare(strict_types=1);

namespace TeensyPHP\Command;

use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'make:command',
    description: 'Generate a new command',
    help: 'This command allows you to create a new command',
    usages: ['make:command app:create:models']
)]
class MakeCommand
{
    public function __invoke(OutputInterface $output, #[Argument('Name of command')] string $commandName): int
    {

        $commandClassName = str_replace(
            search: ' ',
            replace: '',
            subject: ucwords(
                string: str_replace(
                    search: ':',
                    replace: ' ',
                    subject: strtolower($commandName)
                )
            )
        );
        $commandDescription = 'This command allows you to ...';
        $commandPath = getcwd() . DIRECTORY_SEPARATOR. 'App'.DIRECTORY_SEPARATOR.'Commands'.DIRECTORY_SEPARATOR.$commandClassName.'.php';
        $teensyphpRootDir = dirname(dirname(__DIR__));
        $commandTemplatePath =$teensyphpRootDir.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'CommandTemplate.php';
        if (!file_exists($commandTemplatePath)) {
            $output->writeln('<fg=red>Can not find template file</>');
            return Command::FAILURE;
        }

        if(file_exists($commandPath)) {
           $output->writeln('<fg=red>Template file already exists.</>');
            return Command::FAILURE;
        }

        $commandDir = dirname($commandPath);
        if (!file_exists($commandDir)) {
            mkdir($commandDir, 0777, true);
        }
        $commandTemplateRendered = template($commandTemplatePath, [
            'commandName' => $commandName,
            'commandDescription' => $commandDescription,
            'commandClassName' => $commandClassName,
        ]);

        // replace escaped <\?php
        $commandTemplateRendered = str_replace('<\?', '<?', $commandTemplateRendered);

        file_put_contents($commandPath, $commandTemplateRendered);
        $output->writeln('<fg=green>Command ' . $commandName .' created:</>');
        // make path clickable in IDE's
        $output->writeln('at ' . str_replace(getcwd() . DIRECTORY_SEPARATOR, '', $commandPath . ":19:9"));
        return Command::SUCCESS;
    }
}
