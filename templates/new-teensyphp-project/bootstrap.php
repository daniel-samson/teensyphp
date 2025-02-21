<?php

use TeensyPHP\Exceptions\TeensyPHPException;
use TeensyPHP\Utility\Config;
use TeensyPHP\Utility\Database;
use App\Entity\BaseEntity;

require_once(__DIR__ . "/vendor/autoload.php");
require_once(__DIR__ . "/globals.php");
require_once(__DIR__ . "/functions.php");

set_exception_handler(function($exception) {
    handle_exception($exception);
});

// load .env file
Config::loadEnvFile(APP_ROOT );

try {
    BaseEntity::$DB = Database::connect(
        Config::get("DATABASE_ENGINE", "sqlite"),
        Config::get("DATABASE_DATABASE", "teensyblog.sqlite"),
        Config::get("DATABASE_HOST"),
        Config::get("DATABASE_PORT"),
        Config::get("DATABASE_USERNAME"),
        Config::get("DATABASE_PASSWORD"),
    )->connection();
} catch (\Throwable $e) {
    http_response_code(500);
    throw new TeensyPHPException("Failed to connect to database");
}

no_php_headers();

start_session();

// load routes
router(function () {
    use_request_uri();
    require_once APP_ROOT . "/routes/web.php";
    require_once APP_ROOT . "/routes/api.php";
});