<?php

/**
 * Handle exceptions
 * @param Throwable $exception
 */
function handle_exception(Throwable $exception): void
{
    $prefix = "[" . date("Y-m-d H:i:s") . "] [" . get_class($exception) . "] ";
    file_put_contents(__DIR__ . "/error.log", $prefix . $exception->getMessage() . PHP_EOL, FILE_APPEND);
    file_put_contents(__DIR__ . "/error.log", $prefix . $exception->getTraceAsString() . PHP_EOL, FILE_APPEND);
    switch (true) {
        case $exception->getCode() === 404:
            http_response_code(404);
            echo template(__DIR__. "/templates/pages/404.php", []);
            return;
        default:
            http_response_code(500);
            echo template(__DIR__. "/templates/pages/500.php", []);
    }
}

/**
 * Hide PHP headers
 */
function no_php_headers(): void
{
    header_remove("X-Powered-By");
    header_remove("Server");
}

/**
 * Start the session
 */
function start_session(): void
{
    // Set session cookie
    // Allow the user to be logged out after 10 hours of inactivity
    if (!session_id()) {
        $tenHours = 60 * 60 * 10;
        $maxlifetime = $tenHours;
        // rename session cookie to hide that this is a php server
        session_name("SID");
        session_set_cookie_params(
            [
                'lifetime' => $maxlifetime,
                'path' => '/',
                'domain' => $_SERVER['HTTP_HOST'],
//                'secure' => $secure,
//                'httponly' => $httponly,
//                'samesite' => $samesite
            ]
        );
        session_start();
    }
}

