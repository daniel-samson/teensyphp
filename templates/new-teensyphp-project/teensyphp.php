<?php

if (!file_exists('vendor/autoload.php')) {
    echo "Please run 'composer install' first." . PHP_EOL;
    exit(1);
}

require_once "vendor/autoload.php";

use Symfony\Component\Console\Application;
use TeensyPHP\Command\NewProjectCommand;

$application = new Application();

// ... register commands

$application->run();