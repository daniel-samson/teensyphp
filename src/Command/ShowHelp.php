<?php

namespace TeensyPHP\Command;

/**
 * ShowHelp command
 */ 
final class ShowHelp
{
    /**
     * @return void
     */
    public function __invoke()
    {
        echo "Usage: teensyphp command [options]\n";
        echo "Commands:\n";
        echo "  new\t\t Create a new project\n";
        echo "Options:\n";
        echo "  -h, --help\t Display this help message\n";
        stop();
    }
}