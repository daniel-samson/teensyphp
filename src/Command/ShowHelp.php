<?php

namespace TeensyPHP\Command;

final class ShowHelp
{
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