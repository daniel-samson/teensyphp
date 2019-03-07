<?php

/**
 * Call middleware
 * @param callable ...$features
 * @return Closure
 */
function middleware(callable ...$features): callable
{
    return function () use ($features) {
        foreach ($features as $feature) {
            call_user_func_array($feature, array());
        }
    };
}
