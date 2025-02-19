<?php

/**
 * Call middleware
 * @param callable|string ...$features - middleware to call
 * @return Closure
 */
function middleware(callable|string ...$features): callable
{
    return function () use ($features) {
        foreach ($features as $feature) {
            // allow invoking a class (single action middleware)
            if (is_string($feature)) {
                $feature = new $feature();
            }
            call_user_func_array($feature, array());
        }
    };
}
