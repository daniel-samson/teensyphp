<?php

namespace TeensyPHPTests;
class SingleActionMiddleware
{
    public function __invoke()
    {
        $_GET['test_single_action_middleware'] = microtime(true);
    }
}