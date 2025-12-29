<?php
if (file_exists(__DIR__ . "/../vendor/autoload.php")) {
    require_once __DIR__ . "/../vendor/autoload.php";
} else {
    $globalAutoload = trim(shell_exec('composer global config home')) . '/vendor/autoload.php';
    if (file_exists($globalAutoload)) {
        require $globalAutoload;
    }
}

use Symfony\Component\Console\Application;
use TeensyPHP\Command\NewProjectCommand;

$application = new Application();

// ... register commands
$application->addCommand(new NewProjectCommand());

$application->run();